<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('postback_settings')) {
            Schema::create('postback_settings', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->string('network', 100);
                $table->unsignedBigInteger('store_id')->nullable();
                $table->string('secret_key', 100)->nullable();
                $table->text('postback_url')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('postback_settings');
    }
};
