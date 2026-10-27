<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'click_id',
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
