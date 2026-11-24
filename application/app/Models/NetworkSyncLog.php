<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkSyncLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function setting()
    {
        return $this->belongsTo(PostbackSetting::class, 'postback_setting_id');
    }
}
