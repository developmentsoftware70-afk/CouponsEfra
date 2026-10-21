<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($coupon) {
            if (empty($coupon->slug) || $coupon->isDirty('title')) {
                $baseSlug = \Illuminate\Support\Str::slug($coupon->title) ?: 'coupon';
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->where('id', '!=', $coupon->id ?? 0)->exists()) {
                    $slug = $baseSlug . '-' . ($coupon->id ?? mt_rand(100, 999)) . ($counter > 1 ? '-' . $counter : '');
                    $counter++;
                }
                $coupon->slug = $slug;
            }
        });
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    
    }
}
