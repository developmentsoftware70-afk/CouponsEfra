<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'amount'      => 'decimal:2',
        'eligible_at' => 'date',
        'paid_at'     => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get HTML formatted status badge
     */
    public function getStatusBadgeAttribute()
    {
        $status = strtolower($this->status);
        return match($status) {
            'confirmed', 'paid', 'approved', 'completed' => '<span class="badge badge--success">Confirmed</span>',
            'cancelled', 'declined', 'rejected'          => '<span class="badge badge--danger">Cancelled</span>',
            'under_review', 'unreviewed'                 => '<span class="badge badge--primary">Under Review</span>',
            default                                      => '<span class="badge badge--warning">Pending</span>',
        };
    }
}
