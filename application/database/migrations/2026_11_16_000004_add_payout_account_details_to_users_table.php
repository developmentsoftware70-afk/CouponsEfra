<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'payout_method')) {
                $table->string('payout_method', 30)->nullable()->default('bank_transfer');
            }
            if (!Schema::hasColumn('users', 'bank_name')) {
                $table->string('bank_name', 100)->nullable();
            }
            if (!Schema::hasColumn('users', 'bank_account_name')) {
                $table->string('bank_account_name', 100)->nullable();
            }
            if (!Schema::hasColumn('users', 'bank_account_number')) {
                $table->string('bank_account_number', 50)->nullable();
            }
            if (!Schema::hasColumn('users', 'bank_ifsc')) {
                $table->string('bank_ifsc', 30)->nullable();
            }
            if (!Schema::hasColumn('users', 'upi_id')) {
                $table->string('upi_id', 100)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'payout_method',
                'bank_name',
                'bank_account_name',
                'bank_account_number',
                'bank_ifsc',
                'upi_id'
            ]);
        });
    }
};
