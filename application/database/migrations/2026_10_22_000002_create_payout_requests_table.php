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
        if (!Schema::hasTable('payout_requests')) {
            Schema::create('payout_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->decimal('amount', 12, 2);
                $table->string('payment_method', 50)->default('bank_transfer'); // bank_transfer or upi
                $table->string('account_holder_name')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('account_number')->nullable();
                $table->string('ifsc_code', 50)->nullable();
                $table->string('upi_id')->nullable();
                $table->string('status', 30)->default('pending'); // pending, under_review, paid, declined
                $table->string('month_cycle', 50)->nullable(); // e.g. "October 2026"
                $table->date('eligible_at')->nullable(); // e.g. 15th of next month
                $table->string('transaction_id')->nullable(); // UTR / Txn number when paid
                $table->text('admin_feedback')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                $table->index('user_id');
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_requests');
    }
};
