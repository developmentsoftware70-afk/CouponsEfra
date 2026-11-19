<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralLog extends Model
{
    use HasFactory;

    protected $table = 'referral_logs';

    protected $fillable = [
        'referrer_id',
        'referee_id',
        'order_id',
        'order_amount',
        'reward_amount',
        'status',
        'confirmed_at',
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'reward_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
    ];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referee()
    {
        return $this->belongsTo(User::class, 'referee_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function scopePendingPurchase($query)
    {
        return $query->where('status', 'pending_purchase');
    }

    public function scopePendingConfirmation($query)
    {
        return $query->where('status', 'pending_confirmation');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
}
