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
        if (!Schema::hasTable('clicks')) {
            Schema::create('clicks', function (Blueprint $table) {
                $table->id();
                $table->string('click_id', 64)->unique()->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('coupon_id')->nullable()->index();
                $table->unsignedBigInteger('deal_id')->nullable()->index();
                $table->unsignedBigInteger('store_id')->nullable()->index();
                $table->unsignedBigInteger('order_id')->nullable()->index();
                $table->string('campaign')->nullable()->index();
                $table->string('utm_source')->nullable();
                $table->string('utm_medium')->nullable();
                $table->string('utm_campaign')->nullable();
                $table->string('utm_term')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->string('device_type', 20)->nullable();
                $table->string('browser', 50)->nullable();
                $table->string('os', 50)->nullable();
                $table->text('user_agent')->nullable();
                $table->text('destination_url')->nullable();
                $table->text('final_url')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'click_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('click_id', 64)->nullable()->after('id')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'click_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('click_id');
            });
        }
        Schema::dropIfExists('clicks');
    }
};
