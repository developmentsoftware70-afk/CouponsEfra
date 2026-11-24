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
                $info = getIpInfo();
                $userLogin->longitude    = is_array(@$info['long']) ? @implode(',', $info['long']) : (string) (@$info['long'] ?? '');
                $userLogin->latitude     = is_array(@$info['lat']) ? @implode(',', $info['lat']) : (string) (@$info['lat'] ?? '');
                $userLogin->city         = is_array(@$info['city']) ? @implode(',', $info['city']) : (string) (@$info['city'] ?? '');
                $userLogin->country_code = is_array(@$info['code']) ? @implode(',', $info['code']) : (string) (@$info['code'] ?? '');
                $userLogin->country      = is_array(@$info['country']) ? @implode(',', $info['country']) : (string) (@$info['country'] ?? '');
            }

            $userAgent = osBrowser();
            $userLogin->user_id = $user->id;
            $userLogin->user_ip = $ip;
            $userLogin->browser = @$userAgent['browser'];
            $userLogin->os = @$userAgent['os_platform'];

            // Capture Click ID and Sub ID from request, session, or latest user click
            $clickId = request('click_id') 
                ?? request('clickid') 
                ?? session('click_id') 
                ?? (class_exists(Click::class) ? Click::where('user_id', $user->id)->latest()->value('click_id') : null);

            $subId = request('sub_id') 
                ?? request('subid') 
                ?? request('sub1') 
                ?? session('sub_id') 
                ?? session('reference')
                ?? @$user->ref_by;

            $userLogin->click_id = $clickId;
            $userLogin->sub_id   = $subId;

            $userLogin->save();

            return $userLogin;
        } catch (\Exception $e) {
            \Log::error('UserLogin record error: ' . $e->getMessage());
        }
    }
}
