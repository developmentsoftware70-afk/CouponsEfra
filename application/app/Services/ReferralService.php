<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ReferralLog;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;

class ReferralService
{
    /**
     * Generate a unique 8-character referral code (e.g. CE87A49X)
     */
    public static function generateUniqueCode(): string
    {
        do {
            $code = 'CE' . strtoupper(Str::random(6));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Handle new user registration and link to referrer
     */
    public static function handleRegistration(User $user, ?string $reference = null): void
    {
        $general = gs();

        // Ensure user has a referral code
        if (empty($user->referral_code)) {
            $user->referral_code = self::generateUniqueCode();
            $user->save();
        }

        // Check if referral system is active
        if (isset($general->referral_status) && !$general->referral_status) {
            return;
        }

        // Determine reference parameter if not explicitly passed
        if (empty($reference)) {
            $reference = session()->get('reference') ?? request()->cookie('reference');
        }

        if (empty($reference)) {
            return;
        }

        $reference = trim($reference);

        // Find referrer by referral code or username
        $referrer = User::where('referral_code', $reference)
            ->orWhere('username', $reference)
            ->first();

        // Validate referrer (must exist and not be self)
        if (!$referrer || $referrer->id === $user->id) {
            return;
        }

        // Assign referrer to user if not already set
        if (!$user->ref_by) {
            $user->ref_by = $referrer->id;
            $user->save();
        }

        $rewardAmount = (float) ($general->referral_reward_amount ?? 50.00);

        // Create initial pending log for this referral relationship
        ReferralLog::firstOrCreate(
            [
                'referrer_id' => $referrer->id,
                'referee_id'  => $user->id,
            ],
            [
                'order_id'      => null,
                'order_amount'  => 0.00,
                'reward_amount' => $rewardAmount,
                'status'        => 'pending_purchase',
            ]
        );
    }

    /**
     * Evaluate an order to see if it qualifies for referral reward:
     * - Rule 1: User must have a referrer (ref_by > 0)
     * - Rule 2: Order must meet minimum purchase amount (default ₹500)
     * - Rule 3: Only ONE referral reward per referred user (first qualifying order)
     * - Rule 4: Reward paid only when order is confirmed (protects against cancellation/return fraud)
     */
    public static function evaluateOrder(Order $order): void
    {
        $general = gs();

        if (isset($general->referral_status) && !$general->referral_status) {
            return;
        }

        $referee = User::find($order->user_id);
        if (!$referee || !$referee->ref_by) {
            return;
        }

        $referrer = User::find($referee->ref_by);
        if (!$referrer) {
            return;
        }

        $minPurchase  = (float) ($general->referral_min_purchase ?? 500.00);
        $rewardAmount = (float) ($general->referral_reward_amount ?? 50.00);

        // Check if a reward for this referee has already been confirmed
        $alreadyConfirmed = ReferralLog::where('referee_id', $referee->id)
            ->where('status', 'confirmed')
            ->exists();

        if ($alreadyConfirmed) {
            return; // One-time first order rule
        }

        // Find or create ReferralLog for this relationship
        $log = ReferralLog::where('referee_id', $referee->id)->first();
        if (!$log) {
            $log = new ReferralLog([
                'referrer_id'   => $referrer->id,
                'referee_id'    => $referee->id,
                'reward_amount' => $rewardAmount,
                'status'        => 'pending_purchase',
            ]);
        }

        $orderAmount = (float) $order->amount;
        $orderStatus = strtolower(trim((string) $order->status));

        // If order amount is below minimum threshold (e.g. < ₹500), does not qualify
        if ($orderAmount < $minPurchase) {
            return;
        }

        // Order qualifies (>= ₹500)
        $log->order_id     = $order->id;
        $log->order_amount = $orderAmount;
        $log->reward_amount = $rewardAmount;

        if (in_array($orderStatus, ['confirmed', 'approved', '1', 'completed', 'success'], true)) {
            // ORDER CONFIRMED BY BRAND/ADMIN: Credit ₹50 to Referrer's Wallet
            if ($log->status !== 'confirmed') {
                $referrer->balance += $rewardAmount;
                $referrer->save();

                $trx = new Transaction();
                $trx->user_id      = $referrer->id;
                $trx->amount       = $rewardAmount;
                $trx->post_balance = $referrer->balance;
                $trx->charge       = 0;
                $trx->trx_type     = '+';
                $trx->details      = 'Referral reward for ' . ($referee->username ?: 'friend') . '\'s first qualifying order #' . ($order->order_number ?: $order->id);
                $trx->trx          = getTrx();
                $trx->remark       = 'referral_reward';
                $trx->save();

                $log->status       = 'confirmed';
                $log->confirmed_at = now();
                $log->save();

                // Attempt to notify referrer
                try {
                    notify($referrer, 'REFERRAL_REWARD', [
                        'amount'          => showAmount($rewardAmount),
                        'currency'        => $general->cur_text ?? 'INR',
                        'friend_username' => $referee->username,
                        'post_balance'    => showAmount($referrer->balance),
                    ]);
                } catch (\Throwable $e) {}
            }
        } elseif (in_array($orderStatus, ['rejected', 'cancelled', '2', 'refunded'], true)) {
            // ORDER CANCELLED / RETURNED: Mark as rejected
            if ($log->order_id === $order->id) {
                if ($log->status === 'confirmed') {
                    // Reversal if previously credited
                    $referrer->balance = max(0, $referrer->balance - $rewardAmount);
                    $referrer->save();

                    $trx = new Transaction();
                    $trx->user_id      = $referrer->id;
                    $trx->amount       = $rewardAmount;
                    $trx->post_balance = $referrer->balance;
                    $trx->charge       = 0;
                    $trx->trx_type     = '-';
                    $trx->details      = 'Referral reward reversed for cancelled order #' . ($order->order_number ?: $order->id);
                    $trx->trx          = getTrx();
                    $trx->remark       = 'referral_reversal';
                    $trx->save();
                }

                $log->status = 'rejected';
                $log->save();
            }
        } else {
            // ORDER PENDING (Within return window or awaiting brand approval)
            if ($log->status !== 'confirmed') {
                $log->status = 'pending_confirmation';
                $log->save();
            }
        }
    }
}
