<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Events\OrderStatusUpdated;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Orders & Tracking';
        $query = Order::with('user')->latest();

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%$search%")
                  ->orWhere('id', $search)
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('username', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                  });
            });
        }

        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(getPaginate());
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,unreviewed,cancelled,processing,shipped,delivered,failed',
            'amount' => 'nullable|numeric|min:0',
            'product_name' => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        $previousStatus = $order->status;
        $order->status = $request->status;

        if ($request->has('amount') && $request->amount !== null) {
            $order->amount = $request->amount;
        }

        if ($request->has('payout_amount') && $request->payout_amount !== null) {
            $order->payout_amount = $request->payout_amount;
        }

        if ($request->has('product_name') && !empty($request->product_name)) {
            $order->product_name = $request->product_name;
        }

        $order->save();

        if (class_exists(OrderEvent::class)) {
            try {
                OrderEvent::create([
                    'order_id' => $order->id,
                    'status'   => $request->status,
                    'detail'   => 'Status updated by Admin',
                    'event_at' => now(),
                ]);
            } catch (\Exception $e) {}
        }

        if (class_exists(OrderStatusUpdated::class)) {
            try {
                event(new OrderStatusUpdated($order, $previousStatus));
            } catch (\Exception $e) {}
        }

        $notify[] = ['success', 'Order status & details updated successfully'];
        return back()->withNotify($notify);
    }
}
