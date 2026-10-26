<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('coupons', 'impression_count')) {
                $table->unsignedBigInteger('impression_count')->default(0)->after('click_count');
            }
        });

        Schema::table('deals', function (Blueprint $table) {
            if (!Schema::hasColumn('deals', 'click_count')) {
                $table->unsignedBigInteger('click_count')->default(0)->after('status');
            }
            if (!Schema::hasColumn('deals', 'impression_count')) {
                $table->unsignedBigInteger('impression_count')->default(0)->after('click_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (Schema::hasColumn('coupons', 'impression_count')) {
                $table->dropColumn('impression_count');
            }
        });

        Schema::table('deals', function (Blueprint $table) {
            if (Schema::hasColumn('deals', 'click_count')) {
                $table->dropColumn('click_count');
            }
            if (Schema::hasColumn('deals', 'impression_count')) {
                $table->dropColumn('impression_count');
            }
        });
    }
};
