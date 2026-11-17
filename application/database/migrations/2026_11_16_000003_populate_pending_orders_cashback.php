<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Order;
use App\Models\Coupon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $orders = Order::where(function($q) {
            $q->where('payout_amount', '<=', 0)
              ->orWhere('amount', '<=', 0);
        })->get();

        foreach ($orders as $order) {
            $product = $order->product_name ?? '';
            $pLower = strtolower($product);
            $amount = (float) $order->amount;
            $payout = (float) $order->payout_amount;

            // 1. Determine Sale Value
            if ($amount <= 0) {
                if (str_contains($pLower, 'drinkprime')) {
                    $amount = 349.00;
                } elseif (str_contains($pLower, 'bhim')) {
                    $amount = 100.00;
                } elseif (str_contains($pLower, 'neeman')) {
                    $amount = 899.00;
                } elseif (str_contains($pLower, 'flipkart')) {
                    $amount = 1499.00;
                } elseif (str_contains($pLower, 'myntra')) {
                    $amount = 1299.00;
                } elseif (str_contains($pLower, 'noise')) {
                    $amount = 1799.00;
                } elseif (str_contains($pLower, 'bacca bucci')) {
                    $amount = 999.00;
                } elseif (str_contains($pLower, 'gnc')) {
                    $amount = 1499.00;
                } elseif (str_contains($pLower, 'nilkamal')) {
                    $amount = 499.00;
                } elseif (str_contains($pLower, 'dot & key')) {
                    $amount = 699.00;
                } elseif (str_contains($pLower, 'credit card') || str_contains($pLower, 'novio') || str_contains($pLower, 'au ')) {
                    $amount = 500.00;
                } elseif (str_contains($pLower, 'cashify')) {
                    $amount = 500.00;
                } elseif (str_contains($pLower, 'insurance')) {
                    $amount = 1000.00;
                } else {
                    $amount = 349.00;
                }
            }

            // 2. Determine Cashback Earning Value
            if ($payout <= 0) {
                if (preg_match('/Flat\s*₹?\s*(\d+)\s*Cashback/i', $product, $m)) {
                    $payout = (float) $m[1];
                } elseif (preg_match('/Extra\s*(\d+(?:\.\d+)?)\s*%\s*Cashback/i', $product, $m)) {
                    $percent = (float) $m[1];
                    $payout = round(($amount * $percent) / 100, 2);
                } elseif (str_contains($pLower, 'drinkprime')) {
                    $payout = 50.00;
                } elseif (str_contains($pLower, 'bhim')) {
                    $payout = 50.00;
                } elseif (str_contains($pLower, 'neeman')) {
                    $payout = 75.00;
                } elseif (str_contains($pLower, 'flipkart')) {
                    $payout = 120.00;
                } elseif (str_contains($pLower, 'myntra')) {
                    $payout = 130.00;
                } elseif (str_contains($pLower, 'noise')) {
                    $payout = 125.00;
                } elseif (str_contains($pLower, 'bacca bucci')) {
                    $payout = 150.00;
                } elseif (str_contains($pLower, 'gnc')) {
                    $payout = 120.00;
                } elseif (str_contains($pLower, 'nilkamal')) {
                    $payout = 50.00;
                } elseif (str_contains($pLower, 'dot & key')) {
                    $payout = 84.00;
                } elseif (str_contains($pLower, 'credit card') || str_contains($pLower, 'novio') || str_contains($pLower, 'au ')) {
                    $payout = 100.00;
                } elseif (str_contains($pLower, 'cashify')) {
                    $payout = 75.00;
                } elseif (str_contains($pLower, 'insurance')) {
                    $payout = 150.00;
                } else {
                    $payout = 50.00;
                }
            }

            $order->amount = $amount;
            $order->payout_amount = $payout;
            $order->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};

