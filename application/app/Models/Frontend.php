<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frontend extends Model
{
    protected $casts = [
        'data_values' => 'object'
    ];
    
    public static function scopeGetContent($data_keys)
    {
        return Frontend::where('data_keys', $data_keys);
    }

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('frontend_cache_version');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('frontend_cache_version');
        });
    }
}
