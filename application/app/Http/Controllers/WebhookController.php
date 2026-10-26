<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderEvent;
use App\Events\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle merchant callback to update order status.
     * Expected payload: order_id, status, detail (optional)
     */
    public function handleMerchantCallback(Request $request)
    {
        $orderId = $request->input('order_id') ?? $request->input('track_id');

        // If track_id format like username_userId_orderId
        if ($orderId && !is_numeric($orderId) && str_contains($orderId, '_')) {
            $parts = explode('_', $orderId);
            $orderId = end($parts);
        }

        $request->merge(['order_id' => $orderId]);

        $validated = $request->validate([
            'order_id'     => 'required|integer|exists:orders,id',
            'status'       => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,failed',
            'amount'       => 'nullable|numeric|min:0',
            'product_name' => 'nullable|string|max:255',
            'detail'       => 'sometimes|string',
        ]);

        $order = Order::findOrFail($validated['order_id']);
        $previousStatus = $order->status;
        $order->status = $validated['status'];

        if (isset($validated['amount']) && $validated['amount'] > 0) {
            $order->amount = $validated['amount'];
        }

        if (!empty($validated['product_name'])) {
            $order->product_name = $validated['product_name'];
        }

        $order->save();

        // Record event
        if (class_exists(OrderEvent::class)) {
            try {
                OrderEvent::create([
                    'order_id' => $order->id,
                    'status'   => $validated['status'],
                    'detail'   => $validated['detail'] ?? 'Webhook update from merchant',
                    'event_at' => now(),
                ]);
            } catch (\Exception $e) {
                // ignore
            }
        }

        // Broadcast real‑time update if event exists
        if (class_exists(OrderStatusUpdated::class)) {
            try {
                event(new OrderStatusUpdated($order, $previousStatus));
            } catch (\Exception $e) {
                // ignore
            }
        }

        Log::info("Order {$order->id} updated: Status = {$order->status}, Amount = {$order->amount}");
        return response()->json([
            'status'  => 'success',
            'message' => 'Order updated successfully',
            'order'   => [
                'id'           => $order->id,
                'status'       => $order->status,
                'amount'       => $order->amount,
                'product_name' => $order->product_name,
            ]
        ], 200);
    }
}
