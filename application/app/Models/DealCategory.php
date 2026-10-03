<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealCategory extends Model
{
    use HasFactory;

    public function deals()
    {
        return $this->hasMany(Deal::class)
                    ->where('status', 1)
                    ->where('is_show', 1)
                    ->orderBy('created_at', 'desc');
}
}
