<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add order_number column to orders table if not present
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'order_number')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('order_number', 64)->nullable()->after('click_id')->index();
            });
        }

        // 2. Create order_daily_counters table for atomic lock-free sequencing
        if (!Schema::hasTable('order_daily_counters')) {
            Schema::create('order_daily_counters', function (Blueprint $table) {
                $table->date('order_date')->primary();
                $table->unsignedBigInteger('current_seq')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'order_number')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('order_number');
            });
        }

        Schema::dropIfExists('order_daily_counters');
    }
};

