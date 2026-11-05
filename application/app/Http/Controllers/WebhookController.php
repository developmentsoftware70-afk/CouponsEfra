<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Click;
use App\Models\Coupon;
use App\Models\Deal;
use App\Models\User;
use App\Models\OrderEvent;
use App\Events\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle merchant/affiliate callback to record or update real-time purchase orders.
     * Compatible with Trackier, Admitad, Cuelinks, Impact, and custom merchant postbacks.
     */
    public function handleMerchantCallback(Request $request)
    {
        // 0. Auto-clean any 'amp;' prefix from parameter keys (resilient to URL-encoded paste issues)
        $cleanedInputs = [];
        foreach ($request->all() as $k => $v) {
            $cleanK = preg_replace('/^(?:amp;)+/', '', (string) $k);
            $cleanedInputs[$cleanK] = $v;
        }
        $request->merge($cleanedInputs);

        Log::info('Merchant Webhook Callback Received: ', $request->all());

        // 1. Gather all candidate tracking identifiers across various network standards
        $clickCandidates = array_values(array_filter([
            $request->input('click_id'),
            $request->input('p1'),
            $request->input('sub1'),
            $request->input('irclid'),
            $request->input('clickid'),
            $request->input('sub_id'),
            $request->input('aff_sub'),
        ]));

        // 2. Resolve Merchant Transaction / Order ID
        $txnId = $request->input('txn_id') 
            ?? $request->input('order_id') 
            ?? $request->input('track_id') 
            ?? $request->input('transaction_id')
            ?? $request->input('sale_id');

        // If track_id format like username_userId_orderId or clickId
        if ($txnId && !is_numeric($txnId) && str_contains($txnId, '_')) {
            $parts = explode('_', $txnId);
            $txnId = end($parts);
        }

        // 3. Smart Find matching click in clicks table
        $click = null;
        if (!empty($clickCandidates)) {
            $click = Click::whereIn('click_id', $clickCandidates)->first();
        }
        if (!$click && $txnId) {
            $click = Click::where('click_id', $txnId)->orWhere('order_id', $txnId)->first();
        }

        // Resolve active Click ID
        $clickId = $click ? $click->click_id : ($clickCandidates[0] ?? null);

        // Helper to safely parse numbers even with commas or currency symbols (e.g. "1,999.00" -> 1999.00)
        $cleanNum = function ($val) {
            if ($val === null || $val === '') return 0.0;
            if (is_numeric($val)) return (float) $val;
            $clean = preg_replace('/[^\d.]/', '', (string) $val);
            return (float) $clean;
        };

        // 4. Resolve Amount & Payout (strictly dynamic from merchant, ZERO hardcoding)
        $rawAmount = $request->input('amount') 
            ?? $request->input('sale_amount') 
            ?? $request->input('order_amount') 
            ?? $request->input('cart_value') 
            ?? $request->input('order_value') 
            ?? $request->input('subtotal')
            ?? $request->input('price')
            ?? $request->input('total');

        $rawPayout = $request->input('payout') 
            ?? $request->input('payout_amount') 
            ?? $request->input('commission') 
            ?? $request->input('revenue') 
            ?? $request->input('earning')
            ?? $request->input('aff_payout')
            ?? $request->input('pub_payout');

        $amount = $cleanNum($rawAmount);
        $payout = $cleanNum($rawPayout);

        // 5. Normalize Status
        $rawStatus = strtolower(trim((string) (
            $request->input('status') 
            ?? $request->input('conversion_status') 
            ?? $request->input('order_status') 
            ?? 'confirmed'
        )));

        $status = match($rawStatus) {
            'approved', 'confirmed', 'delivered', 'success', 'paid', '1', '2' => 'confirmed',
            'rejected', 'cancelled', 'canceled', 'failed', 'declined', '0'     => 'cancelled',
            'processing', 'shipped'                                           => 'processing',
            'pending'                                                         => 'pending',
            default                                                           => ($amount > 0 || $payout > 0 ? 'confirmed' : 'pending'),
        };

        // 6. Resolve Product Name and Image
        $productName = $request->input('product_name') 
            ?? $request->input('adv_name') 
            ?? $request->input('campaign_name') 
            ?? $request->input('title')
            ?? $request->input('item_name');

        $productImage = $request->input('product_image') 
            ?? $request->input('image') 
            ?? $request->input('img_url') 
            ?? $request->input('image_url');

        // Fallback product info from clicked Coupon / Deal if available
        $coupon = ($click && $click->coupon_id) ? Coupon::with('store')->find($click->coupon_id) : null;
        $deal   = ($click && $click->deal_id)   ? Deal::find($click->deal_id) : null;

        if (empty($productName)) {
            $productName = $coupon->title ?? ($deal->title ?? 'Verified Merchant Purchase');
        }
        if (empty($productImage)) {
            $productImage = $coupon->image ?? (@$coupon->store->image ?? ($deal->image ?? null));
        }

        $currency = $request->input('currency', $request->input('curr', 'INR'));

        // 7. Find existing order or create new real-time purchase order
        $order = null;
        if ($click && $click->order_id) {
            $order = Order::find($click->order_id);
        }
        if (!$order && $click) {
            $order = Order::where('click_id', $click->click_id)->first();
        }
        if (!$order && $clickId) {
            $order = Order::where('click_id', $clickId)->first();
        }
        if (!$order && !empty($clickCandidates)) {
            $order = Order::whereIn('click_id', $clickCandidates)->first();
        }

        // Check by numeric order ID passed via p2, sub2, order_id, txn_id
        $numericCandidates = array_values(array_filter([
            $request->input('p2'),
            $request->input('sub2'),
            $request->input('order_id'),
            $txnId,
        ], fn($v) => !empty($v) && is_numeric($v)));

        if (!$order && !empty($numericCandidates)) {
            $order = Order::whereIn('id', array_map('intval', $numericCandidates))->first();
        }
        if (!$order && !empty($txnId)) {
            $order = Order::where('order_number', $txnId)->first();
        }

        $isNew = false;
        if ($order) {
            // Update existing order with real purchase details
            $previousStatus = $order->status;
            if ($amount > 0) $order->amount = $amount;
            if ($payout > 0) $order->payout_amount = $payout;
            $order->status = $status;
            if ($productName) $order->product_name = $productName;
            if ($productImage) $order->product_image = $productImage;
            if ($currency) $order->currency = $currency;
            $order->save();
        } else {
            // Create a brand new real purchase order in real time!
            $isNew = true;
            $previousStatus = null;
            // Resolve a valid User ID to guarantee foreign key integrity
            $userId = null;
            if ($click && $click->user_id) {
                $userId = $click->user_id;
            } elseif (auth()->check()) {
                $userId = auth()->id();
            } elseif ($request->filled('user_id') && User::where('id', $request->input('user_id'))->exists()) {
                $userId = (int) $request->input('user_id');
            } elseif ($request->filled('userId') && User::where('id', $request->input('userId'))->exists()) {
                $userId = (int) $request->input('userId');
            } else {
                // Try extracting user ID from source, track_id, or p2 (e.g. "softwaredevelopment168_11")
                $combined = ($request->input('source') ?? '') . ' ' . ($request->input('track_id') ?? '') . ' ' . ($request->input('p2') ?? '');
                if (preg_match('/_(\d+)\b/', $combined, $matches)) {
                    $potentialUid = (int) $matches[1];
                    if (User::where('id', $potentialUid)->exists()) {
                        $userId = $potentialUid;
                    }
                }
            }
            if (!$userId) {
                $userId = User::value('id') ?? 1;
            }

            $merchantId = $click ? $click->store_id : null;
            $source = $click ? ($click->coupon_id ? 'coupon_section' : ($click->deal_id ? 'deal_section' : 'affiliate')) : 'affiliate';

            $order = Order::create([
                'click_id'      => $clickId,
                'user_id'       => $userId,
                'merchant_id'   => $merchantId,
                'product_name'  => $productName,
                'product_image' => $productImage,
                'amount'        => $amount,
                'payout_amount' => $payout,
                'currency'      => $currency,
                'status'        => $status,
                'source'        => $source,
            ]);

            if ($click) {
                $click->order_id = $order->id;
                $click->save();
            }
        }

        // 8. Record Order Event
        try {
            OrderEvent::create([
                'order_id' => $order->id,
                'status'   => $status,
                'detail'   => $request->input('detail') ?? ($isNew ? 'Real-time order created via merchant postback' : 'Order status updated via merchant postback'),
                'event_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Ignore event log error
        }

        // 9. Fire Realtime Broadcast Event
        try {
            if (class_exists(OrderStatusUpdated::class)) {
                event(new OrderStatusUpdated($order, $previousStatus));
            }
        } catch (\Throwable $e) {
            // Ignore event broadcast error
        }

        Log::info("Real-time Purchase Processed: Order #{$order->id}, User #{$order->user_id}, Amount: {$order->amount}, Payout: {$order->payout_amount}, Status: {$order->status}");

        return response()->json([
            'status'  => 'success',
            'message' => 'Real-time purchase order processed successfully',
            'order'   => [
                'id'            => $order->id,
                'user_id'       => $order->user_id,
                'product_name'  => $order->product_name,
                'product_image' => $order->product_image,
                'amount'        => $order->amount,
                'payout_amount' => $order->payout_amount,
                'currency'      => $order->currency,
                'status'        => $order->status,
            ]
        ], 200);
    }
}