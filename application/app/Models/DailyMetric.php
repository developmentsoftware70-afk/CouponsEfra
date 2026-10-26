<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DailyMetric extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'metric_date' => 'date',
        'clicks'      => 'integer',
        'impressions' => 'integer',
    ];

    /**
     * Increment impressions for today
     */
    public static function incrementImpressions(int $count = 1)
    {
        if ($count <= 0) return;
        $today = Carbon::today()->toDateString();
        self::upsert(
            [['metric_date' => $today, 'impressions' => $count, 'clicks' => 0, 'created_at' => now(), 'updated_at' => now()]],
            ['metric_date'],
            ['impressions' => \Illuminate\Support\Facades\DB::raw("impressions + {$count}"), 'updated_at' => now()]
        );
    }

    /**
     * Increment clicks for today
     */
    public static function incrementClicks(int $count = 1)
    {
        if ($count <= 0) return;
        $today = Carbon::today()->toDateString();
        self::upsert(
            [['metric_date' => $today, 'clicks' => $count, 'impressions' => 0, 'created_at' => now(), 'updated_at' => now()]],
            ['metric_date'],
            ['clicks' => \Illuminate\Support\Facades\DB::raw("clicks + {$count}"), 'updated_at' => now()]
        );
    }
}
