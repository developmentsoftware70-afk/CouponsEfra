<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'kyc_data' => 'object',
        'ver_code_send_at' => 'datetime'
    ];


    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id','desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status','!=',0);
    }

  
    public function fullname(): Attribute {
        return new Attribute(
            get: fn() => $this->firstname || $this->lastname ? $this->firstname . ' ' . $this->lastname : '@'.$this->username,
        );
    }

    // SCOPES
    public function scopeActive()
    {
        return $this->where('status', 1);
    }

    public function scopeBanned()
    {
        return $this->where('status', 0);
    }

    public function scopeEmailUnverified()
    {
        return $this->where('ev', 0);
    }

    public function scopeMobileUnverified()
    {
        return $this->where('sv', 0);
    }


    public function scopeEmailVerified()
    {
        return $this->where('ev', 1);
    }

    public function scopeMobileVerified()
    {
        return $this->where('sv', 1);
    }

    public function scopeWithBalance()
    {
        return $this->where('balance','>', 0);
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'ref_by');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'ref_by');
    }

    public function referralLogs()
    {
        return $this->hasMany(ReferralLog::class, 'referrer_id')->latest();
    }

    public function getReferralCodeAttribute($value)
    {
        if (empty($value) && $this->id) {
            $code = 'CE' . strtoupper(substr(md5($this->id . ($this->username ?? '') . 'ce_salt'), 0, 6));
            \Illuminate\Support\Facades\DB::table('users')->where('id', $this->id)->update(['referral_code' => $code]);
            $this->attributes['referral_code'] = $code;
            return $code;
        }
        return $value;
    }

    public function getReferralLinkAttribute()
    {
        $code = $this->referral_code ?: ($this->username ?: $this->id);
        return route('home') . '?ref=' . urlencode($code);
    }
}
