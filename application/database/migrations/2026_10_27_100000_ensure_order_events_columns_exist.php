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
        if (Schema::hasTable('order_events')) {
            Schema::table('order_events', function (Blueprint $table) {
                if (!Schema::hasColumn('order_events', 'order_id')) {
                    $table->unsignedBigInteger('order_id')->index()->after('id');
                }
                if (!Schema::hasColumn('order_events', 'status')) {
                    $table->string('status', 50)->nullable()->after('order_id');
                }
                if (!Schema::hasColumn('order_events', 'detail')) {
                    $table->text('detail')->nullable()->after('status');
                }
                if (!Schema::hasColumn('order_events', 'event_at')) {
                    $table->timestamp('event_at')->nullable()->after('detail');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('order_events')) {
            Schema::table('order_events', function (Blueprint $table) {
                if (Schema::hasColumn('order_events', 'event_at')) {
                    $table->dropColumn('event_at');
                }
                if (Schema::hasColumn('order_events', 'detail')) {
                    $table->dropColumn('detail');
                }
                if (Schema::hasColumn('order_events', 'status')) {
                    $table->dropColumn('status');
                }
                if (Schema::hasColumn('order_events', 'order_id')) {
                    $table->dropColumn('order_id');
                }
            });
        }
    }
};
