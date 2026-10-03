<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
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
