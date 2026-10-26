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
        if (!Schema::hasTable('daily_metrics')) {
            Schema::create('daily_metrics', function (Blueprint $table) {
                $table->id();
                $table->date('metric_date')->unique();
                $table->unsignedBigInteger('clicks')->default(0);
                $table->unsignedBigInteger('impressions')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_metrics');
    }
};
