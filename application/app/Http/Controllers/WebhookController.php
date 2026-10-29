<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Click;
use App\Models\Coupon;
use App\Models\Deal;
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
        Log::info('Merchant Webhook Callback Received: ', $request->all());

        // 1. Resolve Click ID from all possible affiliate parameter names
        $clickId = $request->input('click_id') 
            ?? $request->input('p1') 
            ?? $request->input('irclid') 
            ?? $request->input('clickid')
            ?? $request->input('sub1')
            ?? $request->input('sub_id')
            ?? $request->input('aff_sub');

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

        // 3. Find matching click in clicks table
        $click = null;
        if ($clickId) {
            $click = Click::where('click_id', $clickId)->first();
        }
        if (!$click && $txnId) {
            $click = Click::where('click_id', $txnId)->orWhere('order_id', $txnId)->first();
        }

        // 4. Resolve Amount & Payout
        $amount = (float) (
            $request->input('amount') 
            ?? $request->input('sale_amount') 
            ?? $request->input('order_amount') 
            ?? $request->input('cart_value') 
            ?? $request->input('order_value') 
            ?? 0
        );

        $payout = (float) (
            $request->input('payout') 
            ?? $request->input('payout_amount') 
            ?? $request->input('commission') 
            ?? $request->input('revenue') 
            ?? $request->input('earning') 
            ?? 0
        );

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
        if (!$order && $clickId) {
            $order = Order::where('click_id', $clickId)->first();
        }
        if (!$order && is_numeric($txnId)) {
            $order = Order::find((int) $txnId);
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
            $userId = $click ? $click->user_id : (auth()->id() ?? 0);
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