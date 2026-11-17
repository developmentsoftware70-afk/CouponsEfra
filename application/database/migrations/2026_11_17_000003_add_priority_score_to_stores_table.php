<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add priority_score to stores if not exists
        if (!Schema::hasColumn('stores', 'priority_score')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->integer('priority_score')->default(0)->after('status');
            });
        }

        // Set anchor brand priority scores (Tier 1 - Doc ke hisaab se)
        // Amazon = 1000, Flipkart = 950, Myntra = 900, Nykaa = 850
        $anchors = [
            'amazon'   => 1000,
            'flipkart' => 950,
            'myntra'   => 900,
            'nykaa'    => 850,
            'swiggy'   => 800,
            'ajio'     => 750,
            'firstcry' => 700,
            'meesho'   => 650,
        ];

        $stores = DB::table('stores')->where('status', 1)->get(['id', 'name']);
        foreach ($stores as $store) {
            $nameLower = strtolower(trim($store->name));
            foreach ($anchors as $anchor => $score) {
                if (str_contains($nameLower, $anchor)) {
                    DB::table('stores')->where('id', $store->id)->update(['priority_score' => $score]);
                    break;
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('stores', 'priority_score')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn('priority_score');
            });
        }
    }
};
