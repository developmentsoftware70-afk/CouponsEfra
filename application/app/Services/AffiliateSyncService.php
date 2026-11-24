<?php

namespace App\Services;

use App\Models\PostbackSetting;
use App\Models\NetworkSyncLog;
use App\Models\Order;
use App\Models\Click;
use App\Models\OrderEvent;
use App\Events\OrderStatusUpdated;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AffiliateSyncService
{
    /**
     * Run synchronization across all enabled networks or for a specific postback setting.
     *
     * @param int|null $settingId
     * @param array|null $mockData Optional simulated conversion data for testing
     * @return array
     */
    public function syncAll(?int $settingId = null, ?array $mockData = null): array
    {
        $query = PostbackSetting::query();
        if ($settingId) {
            $query->where('id', $settingId);
        } else {
            $query->where('sync_enabled', 1)->where('status', 1);
        }

        $settings = $query->get();
        $results = [];

        foreach ($settings as $setting) {
            $results[$setting->name] = $this->syncNetwork($setting, $mockData);
        }

        return $results;
    }

    /**
     * Synchronize conversions for a single network setting.
     */
    public function syncNetwork(PostbackSetting $setting, ?array $mockData = null): array
    {
        $network = strtolower($setting->network);
        $syncedCount  = 0;
        $updatedCount = 0;
        $createdCount = 0;
        $status       = 'success';
        $message      = '';

        try {
            // 1. Fetch raw conversion records from Network API or Mock data for testing
            $conversions = $mockData ?? $this->fetchConversionsFromApi($setting);

            if (empty($conversions)) {
                $message = "Sync completed. No new or updated conversions returned by {$setting->name}.";
            } else {
                foreach ($conversions as $conv) {
                    $res = $this->processConversionRecord($conv, $setting);
                    $syncedCount++;
                    if ($res === 'updated') $updatedCount++;
                    if ($res === 'created') $createdCount++;
                }
                $message = "Synced {$syncedCount} conversion(s) ({$updatedCount} updated, {$createdCount} recovered via backup sync).";
            }
        } catch (\Throwable $e) {
            $status = 'failed';
            $message = "API Sync failed for {$setting->name}: " . $e->getMessage();
            Log::error($message, ['exception' => $e]);
        }

        // 2. Update PostbackSetting metadata
        $setting->update([
            'last_sync_at'      => now(),
            'last_sync_status'  => $status,
            'last_sync_message' => $message,
        ]);

        // 3. Record in network_sync_logs
        NetworkSyncLog::create([
            'postback_setting_id' => $setting->id,
            'network'             => $setting->network,
            'status'              => $status,
            'synced_count'        => $syncedCount,
            'updated_count'       => $updatedCount,
            'created_count'       => $createdCount,
            'message'             => $message,
        ]);

        return [
            'network' => $setting->network,
            'status'  => $status,
            'synced'  => $syncedCount,
            'updated' => $updatedCount,
            'created' => $createdCount,
            'message' => $message,
        ];
    }

    /**
     * Normalize and process an individual conversion record.
     */
    public function processConversionRecord(array $conv, PostbackSetting $setting): string
    {
        $clickId = $conv['click_id'] ?? ($conv['subid'] ?? ($conv['sub1'] ?? ($conv['p1'] ?? null)));
        $txnId   = $conv['txn_id']   ?? ($conv['order_id'] ?? ($conv['transaction_id'] ?? ($conv['id'] ?? null)));

        if (!$clickId && !$txnId) {
            return 'skipped';
        }

        // Clean amount & payout
        $amount = (float) preg_replace('/[^\d.]/', '', (string) ($conv['amount'] ?? ($conv['sale_amount'] ?? 0)));
        $payout = (float) preg_replace('/[^\d.]/', '', (string) ($conv['payout'] ?? ($conv['commission'] ?? 0)));

        // Normalize status
        $rawStatus = strtolower(trim((string) ($conv['status'] ?? 'pending')));
        $status = match ($rawStatus) {
            'approved', 'confirmed', 'paid', 'delivered', 'success', '1', '2' => 'confirmed',
            'rejected', 'cancelled', 'canceled', 'failed', 'declined', '0'     => 'cancelled',
            'processing', 'shipped'                                           => 'processing',
            default                                                           => 'pending',
        };

        $currency = $conv['currency'] ?? 'INR';
        $productName = $conv['product_name'] ?? ($conv['campaign'] ?? ($setting->store->name ?? 'Verified Purchase'));

        // Smart Find matching Order
        $order = null;
        if ($clickId) {
            $order = Order::where('click_id', $clickId)->first();
        }
        if (!$order && $txnId) {
            $order = Order::where('order_number', $txnId)
                ->orWhere('id', is_numeric($txnId) ? (int)$txnId : 0)
                ->first();
        }

        // ==========================================================
        // CASE A: Order Already Exists -> Update Status & Amounts
        // ==========================================================
        if ($order) {
            $previousStatus = $order->status;
            $changed = false;

            if ($order->status !== $status) {
                $order->status = $status;
                $changed = true;
            }
            if ($amount > 0 && $order->amount != $amount) {
                $order->amount = $amount;
                $changed = true;
            }
            if ($payout > 0 && $order->payout_amount != $payout) {
                $order->payout_amount = $payout;
                $changed = true;
            }

            if ($changed) {
                $order->save();

                // Record Event Log
                try {
                    OrderEvent::create([
                        'order_id' => $order->id,
                        'status'   => $status,
                        'detail'   => "Status updated from '{$previousStatus}' to '{$status}' via periodic network API sync",
                        'event_at' => now(),
                    ]);
                } catch (\Throwable $e) {}

                // Broadcast & Referral Reward Evaluation on Approval
                if ($status === 'confirmed') {
                    ReferralService::evaluateOrder($order);
                }
                if (class_exists(OrderStatusUpdated::class)) {
                    event(new OrderStatusUpdated($order, $previousStatus));
                }

                return 'updated';
            }

            return 'unmodified';
        }

        // ==========================================================
        // CASE B: Order Missed -> Backup Sync Recovery!
        // ==========================================================
        $click = null;
        if ($clickId) {
            $click = Click::where('click_id', $clickId)->first();
        }

        // Resolve user
        $userId = $click ? $click->user_id : null;
        if (!$userId && isset($conv['user_id'])) {
            $userId = (int) $conv['user_id'];
        }
        if (!$userId) {
            $userId = \App\Models\User::value('id') ?? 1;
        }

        $order = Order::create([
            'click_id'      => $clickId,
            'order_number'  => $txnId ?: ('SYNC_' . time() . '_' . rand(100, 999)),
            'user_id'       => $userId,
            'merchant_id'   => $setting->store_id ?: ($click->store_id ?? null),
            'product_name'  => $productName,
            'amount'        => $amount,
            'payout_amount' => $payout,
            'currency'      => $currency,
            'status'        => $status,
            'source'        => 'api_backup_sync',
        ]);

        if ($click) {
            $click->order_id = $order->id;
            $click->save();
        }

        try {
            OrderEvent::create([
                'order_id' => $order->id,
                'status'   => $status,
                'detail'   => "Real-time order recovered via automated Backup API sync (Missed postback recovered)",
                'event_at' => now(),
            ]);
        } catch (\Throwable $e) {}

        if ($status === 'confirmed') {
            ReferralService::evaluateOrder($order);
        }

        return 'created';
    }

    /**
     * Fetch conversions from a real Network API endpoint.
     */
    protected function fetchConversionsFromApi(PostbackSetting $setting): array
    {
        $network  = strtolower($setting->network);
        $apiKey   = $setting->api_key;
        $endpoint = $setting->api_endpoint;

        if (empty($endpoint) && empty($apiKey)) {
            return [];
        }

        $startDate = now()->subDays(30)->toDateString();
        $endDate   = now()->toDateString();

        $response = match ($network) {
            'trackier' => Http::withHeaders(['x-api-key' => $apiKey])
                ->timeout(15)
                ->get($endpoint ?: "https://api.trackier.com/v2/reports/conversions", [
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                ]),

            'cuelinks' => Http::withToken($apiKey)
                ->timeout(15)
                ->get($endpoint ?: "https://api.cuelinks.com/v2/transactions.json", [
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                ]),

            'admitad' => Http::withToken($apiKey)
                ->timeout(15)
                ->get($endpoint ?: "https://api.admitad.com/statistics/actions/", [
                    'date_start' => $startDate,
                    'date_end'   => $endDate,
                ]),

            default => Http::withHeaders(['Authorization' => 'Bearer ' . $apiKey])
                ->timeout(15)
                ->get($endpoint, [
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                ]),
        };

        if (!$response->successful()) {
            throw new \Exception("API returned HTTP " . $response->status() . ": " . $response->body());
        }

        $data = $response->json();

        // Normalize common JSON response payload structures
        return $data['conversions'] ?? ($data['data'] ?? ($data['results'] ?? ($data['transactions'] ?? [])));
    }
}
