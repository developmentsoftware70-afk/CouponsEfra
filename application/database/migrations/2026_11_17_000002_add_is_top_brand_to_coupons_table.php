<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('coupons', 'is_top_brand')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->tinyInteger('is_top_brand')->default(0)->after('is_popular');
            });
        }

        // Initialize is_top_brand for existing popular or exclusive coupons
        DB::statement("UPDATE coupons SET is_top_brand = 1 WHERE is_exclusive = 1 OR is_popular = 1 OR is_featured = 1");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('coupons', 'is_top_brand')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->dropColumn('is_top_brand');
            });
        }
    }
};
