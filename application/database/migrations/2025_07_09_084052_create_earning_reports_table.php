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
        Schema::create('earning_reports', function (Blueprint $table) {
            $table->id();
            $table->string('deal_type');
            $table->integer('user_id')->default(0);
            $table->string('compaign_name');
            $table->string('sale_amount');
            $table->string('earning_amount');
            $table->string('report_type');
            $table->string('report_file')->nullable();
            $table->boolean('payment_status')->default(1);   // Show on front
            $table->boolean('status')->default(1); // Active status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('earning_reports');
    }
};
