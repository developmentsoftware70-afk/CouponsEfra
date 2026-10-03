<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;
    public function deal_category()
    {
        return $this->belongsTo(DealCategory::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
}
