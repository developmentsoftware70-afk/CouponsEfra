<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Coupon;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('coupons', 'slug')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('title')->index();
            });

            // Populate existing coupons with slugs
            $coupons = Coupon::all();
            foreach ($coupons as $coupon) {
                $baseSlug = Str::slug($coupon->title);
                if (empty($baseSlug)) {
                    $baseSlug = 'coupon';
                }
                $slug = $baseSlug;
                $counter = 1;
                while (Coupon::where('slug', $slug)->where('id', '!=', $coupon->id)->exists()) {
                    $slug = $baseSlug . '-' . $coupon->id . ($counter > 1 ? '-' . $counter : '');
                    $counter++;
                }
                $coupon->slug = $slug;
                $coupon->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('coupons', 'slug')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
