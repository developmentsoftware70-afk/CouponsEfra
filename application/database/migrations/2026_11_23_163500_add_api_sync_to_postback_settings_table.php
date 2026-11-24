<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('postback_settings', 'api_key')) {
            Schema::table('postback_settings', function (Blueprint $table) {
                $table->string('api_key', 255)->nullable();
            });
        }
        if (!Schema::hasColumn('postback_settings', 'api_secret')) {
            Schema::table('postback_settings', function (Blueprint $table) {
                $table->string('api_secret', 255)->nullable();
            });
        }
        if (!Schema::hasColumn('postback_settings', 'api_endpoint')) {
            Schema::table('postback_settings', function (Blueprint $table) {
                $table->string('api_endpoint', 255)->nullable();
            });
        }
        if (!Schema::hasColumn('postback_settings', 'sync_enabled')) {
            Schema::table('postback_settings', function (Blueprint $table) {
                $table->boolean('sync_enabled')->default(false);
            });
        }
        if (!Schema::hasColumn('postback_settings', 'last_sync_at')) {
            Schema::table('postback_settings', function (Blueprint $table) {
                $table->timestamp('last_sync_at')->nullable();
            });
        }
        if (!Schema::hasColumn('postback_settings', 'last_sync_status')) {
            Schema::table('postback_settings', function (Blueprint $table) {
                $table->string('last_sync_status', 50)->nullable();
            });
        }
        if (!Schema::hasColumn('postback_settings', 'last_sync_message')) {
            Schema::table('postback_settings', function (Blueprint $table) {
                $table->text('last_sync_message')->nullable();
            });
        }

        if (!Schema::hasTable('network_sync_logs')) {
            Schema::create('network_sync_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('postback_setting_id')->nullable();
                $table->string('network', 100);
                $table->string('status', 50)->default('success'); // success, failed, running
                $table->integer('synced_count')->default(0);
                $table->integer('updated_count')->default(0);
                $table->integer('created_count')->default(0);
                $table->text('message')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('network_sync_logs');

        Schema::table('postback_settings', function (Blueprint $table) {
            $table->dropColumn([
                'api_key',
                'api_secret',
                'api_endpoint',
                'sync_enabled',
                'last_sync_at',
                'last_sync_status',
                'last_sync_message',
            ]);
        });
    }
};
