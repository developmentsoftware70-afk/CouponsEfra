<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    /*
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class);
    }
        */
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function scopeActive()
    {
        return $this->where('status', 1);
    }
}
