<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to record user login session with IP and device info.
     */
    public static function recordLogin($user)
    {
        try {
            $ip = getRealIP();
            $exist = self::where('user_ip', $ip)->first();
            $userLogin = new self();
            if ($exist) {
                $userLogin->longitude = $exist->longitude;
                $userLogin->latitude = $exist->latitude;
                $userLogin->city = $exist->city;
                $userLogin->country_code = $exist->country_code;
                $userLogin->country = $exist->country;
            } else {
                $info = json_decode(json_encode(getIpInfo()), true);
                $userLogin->longitude = @implode(',', $info['long'] ?? []);
                $userLogin->latitude = @implode(',', $info['lat'] ?? []);
                $userLogin->city = @implode(',', $info['city'] ?? []);
                $userLogin->country_code = @implode(',', $info['code'] ?? []);
                $userLogin->country = @implode(',', $info['country'] ?? []);
            }

            $userAgent = osBrowser();
            $userLogin->user_id = $user->id;
            $userLogin->user_ip = $ip;
            $userLogin->browser = @$userAgent['browser'];
            $userLogin->os = @$userAgent['os_platform'];
            $userLogin->save();

            return $userLogin;
        } catch (\Exception $e) {
            \Log::error('UserLogin record error: ' . $e->getMessage());
        }
    }
}
