<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('clicks') && !Schema::hasColumn('clicks', 'sub_id')) {
            Schema::table('clicks', function (Blueprint $table) {
                $table->string('sub_id', 64)->nullable()->after('click_id')->index();
            });

            // Backfill existing clicks with matching sub_... format
            \Illuminate\Support\Facades\DB::table('clicks')
                ->whereNull('sub_id')
                ->where('click_id', 'LIKE', 'clk_%')
                ->update([
                    'sub_id' => \Illuminate\Support\Facades\DB::raw("CONCAT('sub_', SUBSTRING(click_id, 5))")
                ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('clicks') && Schema::hasColumn('clicks', 'sub_id')) {
            Schema::table('clicks', function (Blueprint $table) {
                $table->dropColumn('sub_id');
            });
        }
    }
};
