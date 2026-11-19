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
        // 1. Add referral_code to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code', 32)->nullable()->unique()->after('ref_by');
            }
        });

        // 2. Add referral config columns to general_settings table
        Schema::table('general_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('general_settings', 'referral_status')) {
                $table->tinyInteger('referral_status')->default(1)->comment('1=active, 0=inactive');
            }
            if (!Schema::hasColumn('general_settings', 'referral_reward_amount')) {
                $table->decimal('referral_reward_amount', 10, 2)->default(50.00)->comment('Reward per qualified referral');
            }
            if (!Schema::hasColumn('general_settings', 'referral_min_purchase')) {
                $table->decimal('referral_min_purchase', 10, 2)->default(500.00)->comment('Min order amount required to qualify');
            }
        });

        // 3. Create referral_logs table
        if (!Schema::hasTable('referral_logs')) {
            Schema::create('referral_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('referrer_id')->index();
                $table->unsignedBigInteger('referee_id')->index();
                $table->unsignedBigInteger('order_id')->nullable()->index();
                $table->decimal('order_amount', 10, 2)->default(0.00);
                $table->decimal('reward_amount', 10, 2)->default(50.00);
                $table->string('status', 30)->default('pending_purchase')->index()->comment('pending_purchase, pending_confirmation, confirmed, rejected');
                $table->timestamp('confirmed_at')->nullable();
                $table->timestamps();

                $table->foreign('referrer_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('referee_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_logs');

        Schema::table('general_settings', function (Blueprint $table) {
            if (Schema::hasColumn('general_settings', 'referral_status')) {
                $table->dropColumn('referral_status');
            }
            if (Schema::hasColumn('general_settings', 'referral_reward_amount')) {
                $table->dropColumn('referral_reward_amount');
            }
            if (Schema::hasColumn('general_settings', 'referral_min_purchase')) {
                $table->dropColumn('referral_min_purchase');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'referral_code')) {
                $table->dropColumn('referral_code');
            }
        });
    }
};
