<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Click extends Model
{
    protected $fillable = [
        'click_id',
        'user_id',
        'coupon_id',
        'deal_id',
        'store_id',
        'order_id',
        'campaign',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'ip_address',
        'device_type',
        'browser',
        'os',
        'user_agent',
        'destination_url',
        'final_url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
