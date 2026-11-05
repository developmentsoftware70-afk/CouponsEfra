<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'click_id',
        'order_number',
        'user_id',
        'merchant_id',
        'merchant_order_id',
        'product_name',
        'product_image',
        'amount',
        'payout_amount',
        'currency',
        'status',
        'source',
    ];

    /**
     * The "booted" method of the model.
     * Auto-generates order_number like ORD-YYYYMMDD-000001
     */
    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber();
            }
        });
    }

    /**
     * Generate daily sequential order number (e.g. ORD-20261103-000001)
     */
    public static function generateOrderNumber($date = null)
    {
        $today = $date ? date('Ymd', strtotime($date)) : date('Ymd');
        $prefix = "ORD-{$today}-";

        $latest = static::where('order_number', 'LIKE', "{$prefix}%")
            ->orderByDesc('id')
            ->value('order_number');

        $nextSeq = 1;
        if ($latest && preg_match('/-(\d+)$/', $latest, $m)) {
            $nextSeq = (int) $m[1] + 1;
        }

        return $prefix . str_pad($nextSeq, 6, '0', STR_PAD_LEFT);
    }

    /**
     * User who placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Merchant associated with the order (if any).
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    /**
     * Get resolved image URL for the product or store.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->product_image) {
            return null;
        }

        if (str_starts_with($this->product_image, 'http://') || str_starts_with($this->product_image, 'https://')) {
            return $this->product_image;
        }

        $storePath = getFilePath('store') . '/' . $this->product_image;
        if (file_exists($storePath) || file_exists(base_path('../' . $storePath))) {
            return getImage($storePath);
        }

        $couponPath = getFilePath('coupon') . '/' . $this->product_image;
        if (file_exists($couponPath) || file_exists(base_path('../' . $couponPath))) {
            return getImage($couponPath);
        }

        $dealPath = getFilePath('deal') . '/' . $this->product_image;
        if (file_exists($dealPath) || file_exists(base_path('../' . $dealPath))) {
            return getImage($dealPath);
        }

        return getImage($storePath);
    }
}
