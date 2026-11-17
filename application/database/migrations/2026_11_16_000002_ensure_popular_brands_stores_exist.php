<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Store;
use App\Models\Coupon;
use App\Models\Frontend;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ensure Amazon, Myntra, Nykaa stores exist with correct IDs
        $storesToSync = [
            [
                'id' => 30035,
                'user_id' => 0,
                'name' => 'Amazon',
                'image' => 'amazon_brand.svg',
                'status' => 1,
                'description' => 'Online Shopping for Electronics, Apparel, Computers, Books, DVDs & more',
                'show_on_front' => 1,
            ],
            [
                'id' => 30036,
                'user_id' => 0,
                'name' => 'Myntra',
                'image' => 'myntra_brand.png',
                'status' => 1,
                'description' => 'Online Shopping for Men, Women & Kids Fashion, Lifestyle and Accessories',
                'show_on_front' => 1,
            ],
            [
                'id' => 30037,
                'user_id' => 0,
                'name' => 'Nykaa',
                'image' => 'nykaa_brand.jpg',
                'status' => 1,
                'description' => 'Buy Cosmetics Products & Beauty Products Online in India',
                'show_on_front' => 1,
            ],
        ];

        foreach ($storesToSync as $sData) {
            $existing = Store::where('id', $sData['id'])->orWhere('name', $sData['name'])->first();
            if (!$existing) {
                DB::table('stores')->insert(array_merge($sData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            } else {
                $existing->status = 1;
                $existing->save();
            }
        }

        // 2. Ensure each has an active coupon so their coupons page is never blank
        $couponsToSync = [
            [
                'id' => 300034,
                'user_id' => 0,
                'title' => 'Amazon Sale & Cashback Offers',
                'price' => '1799.00',
                'slug' => 'amazon-sale-cashback-offers',
                'description' => '<p>Amazon Sale & Cashback Offers - Shop & Save More with Coupons Efra.</p>',
                'category_id' => 11,
                'store_id' => 30035,
                'code' => 'EFRA10',
                'start_date' => '2025-05-01 00:00:00',
                'expire_date' => '2027-05-05 08:21:33',
                'link' => 'https://www.amazon.in',
                'sale' => 'Sale',
                'discount_offer' => 'Upto 70% Off',
                'cashback_amount' => '7',
                'is_featured' => 1,
                'is_exclusive' => 1,
                'is_popular' => 1,
                'is_sale' => 1,
                'is_cashback' => 0,
                'is_earning' => 1,
                'is_background' => 1,
                'image' => '6814bfe8c18c61746190312.png',
                'status' => 1,
            ],
            [
                'id' => 300035,
                'user_id' => 0,
                'title' => 'Myntra Sale & Cashback Offers',
                'price' => '1799.00',
                'slug' => 'myntra-sale-cashback-offers',
                'description' => '<p>Myntra Sale & Cashback Offers - Shop & Save More with Coupons Efra.</p>',
                'category_id' => 11,
                'store_id' => 30036,
                'code' => 'EFRA10',
                'start_date' => '2025-05-01 00:00:00',
                'expire_date' => '2027-05-05 08:21:34',
                'link' => 'https://www.myntra.in',
                'sale' => 'Sale',
                'discount_offer' => 'Upto 80% Off',
                'cashback_amount' => '6',
                'is_featured' => 1,
                'is_exclusive' => 1,
                'is_popular' => 1,
                'is_sale' => 1,
                'is_cashback' => 0,
                'is_earning' => 1,
                'is_background' => 1,
                'image' => '6814bfe8c18c61746190312.png',
                'status' => 1,
            ],
            [
                'id' => 300036,
                'user_id' => 0,
                'title' => 'Nykaa Sale & Cashback Offers',
                'price' => '1799.00',
                'slug' => 'nykaa-sale-cashback-offers',
                'description' => '<p>Nykaa Sale & Cashback Offers - Shop & Save More with Coupons Efra.</p>',
                'category_id' => 11,
                'store_id' => 30037,
                'code' => 'EFRA10',
                'start_date' => '2025-05-01 00:00:00',
                'expire_date' => '2027-05-05 08:21:35',
                'link' => 'https://www.nykaa.in',
                'sale' => 'Sale',
                'discount_offer' => 'Upto 50% Off',
                'cashback_amount' => '6',
                'is_featured' => 1,
                'is_exclusive' => 1,
                'is_popular' => 1,
                'is_sale' => 1,
                'is_cashback' => 0,
                'is_earning' => 1,
                'is_background' => 1,
                'image' => '6814bfe8c18c61746190312.png',
                'status' => 1,
            ],
        ];

        foreach ($couponsToSync as $cData) {
            $existingC = Coupon::where('store_id', $cData['store_id'])->first();
            if (!$existingC) {
                DB::table('coupons')->insert(array_merge($cData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // 3. Clean any hardcoded domain names from Frontend popular_brands.element
        $allStores = Store::where('status', 1)->get();
        foreach (Frontend::where('data_keys', 'popular_brands.element')->get() as $elem) {
            $vals = (array) $elem->data_values;
            $bName = strtolower(trim(@$vals['brand_name']));
            $matched = $allStores->first(function($s) use ($bName) {
                $n = strtolower(trim($s->name));
                return $n === $bName || str_starts_with($n, $bName) || str_starts_with($bName, $n);
            });
            if ($matched) {
                $vals['link'] = route('store.coupons', $matched->id, false);
            }
            $elem->data_values = $vals;
            $elem->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe no-op
    }
};

