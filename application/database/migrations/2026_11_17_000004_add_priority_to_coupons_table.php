<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('coupons', 'priority')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->integer('priority')->default(0)->after('is_top_brand');
            });
        }

        // Give existing top brand coupons reasonable priority based on their store priority if available
        $stores = DB::table('stores')->get(['id', 'priority_score'])->keyBy('id');
        $coupons = DB::table('coupons')->where('is_top_brand', 1)->get(['id', 'store_id']);
        foreach ($coupons as $coupon) {
            $storePriority = $stores[$coupon->store_id]->priority_score ?? 0;
            if ($storePriority > 0) {
                DB::table('coupons')->where('id', $coupon->id)->update(['priority' => $storePriority]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('coupons', 'priority')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->dropColumn('priority');
            });
        }
    }
};
