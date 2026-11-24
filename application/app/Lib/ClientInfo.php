<?php

namespace App\Lib;

class ClientInfo{

    /**
    * Get requestor IP information
    *
    * @return array
    */
	public static function ipInfo()
	{
	    $ip = getRealIP();

	    // Localhost / private IP check
	    if ($ip == '127.0.0.1' || $ip == '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
	        return [
	            'country' => 'Localhost',
	            'city'    => 'Localhost',
	            'area'    => 'Local',
	            'code'    => 'LOC',
	            'long'    => '',
	            'lat'     => '',
	            'ip'      => $ip,
	            'time'    => date('Y-m-d h:i:s A'),
	        ];
	    }

	    $country = '';
	    $city    = '';
	    $area    = '';
	    $code    = '';
	    $long    = '';
	    $lat     = '';

	    // 1. Try ip-api.com (fast, accurate, reliable free JSON API)
	    try {
	        $ctx = stream_context_create(['http' => ['timeout' => 2]]);
	        $res = @file_get_contents("http://ip-api.com/json/" . $ip, false, $ctx);
	        if ($res) {
	            $json = json_decode($res, true);
	            if (!empty($json) && @$json['status'] === 'success') {
	                $country = $json['country'] ?? '';
	                $city    = $json['city'] ?? '';
	                $area    = $json['regionName'] ?? '';
	                $code    = $json['countryCode'] ?? '';
	                $long    = (string) ($json['lon'] ?? '');
	                $lat     = (string) ($json['lat'] ?? '');
	            }
	        }
	    } catch (\Exception $e) {}

	    // 2. Fallback to geoplugin if ip-api failed
	    if (empty($country)) {
	        try {
	            $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . $ip);
	            if ($xml) {
	                $country = (string) (@$xml->geoplugin_countryName ?? '');
	                $city    = (string) (@$xml->geoplugin_city ?? '');
	                $area    = (string) (@$xml->geoplugin_areaCode ?? '');
	                $code    = (string) (@$xml->geoplugin_countryCode ?? '');
	                $long    = (string) (@$xml->geoplugin_longitude ?? '');
	                $lat     = (string) (@$xml->geoplugin_latitude ?? '');
	            }
	        } catch (\Exception $e) {}
	    }

	    $data['country'] = $country ?: [];
	    $data['city']    = $city ?: [];
	    $data['area']    = $area ?: [];
	    $data['code']    = $code ?: [];
	    $data['long']    = $long ?: [];
	    $data['lat']     = $lat ?: [];
	    $data['ip']      = $ip;
	    $data['time']    = date('Y-m-d h:i:s A');

	    return $data;
	}

    /**
    * Get requestor operating system information
    *
    * @return array
    */
	public static function osBrowser(){
	    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? request()->userAgent() ?? 'Unknown';
	    $osPlatform = "Unknown OS Platform";
	    $osArray = array(
	        '/windows nt 10/i' => 'Windows 10',
	        '/windows nt 6.3/i' => 'Windows 8.1',
	        '/windows nt 6.2/i' => 'Windows 8',
	        '/windows nt 6.1/i' => 'Windows 7',
	        '/windows nt 6.0/i' => 'Windows Vista',
	        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
	        '/windows nt 5.1/i' => 'Windows XP',
	        '/windows xp/i' => 'Windows XP',
	        '/windows nt 5.0/i' => 'Windows 2000',
	        '/windows me/i' => 'Windows ME',
	        '/win98/i' => 'Windows 98',
	        '/win95/i' => 'Windows 95',
	        '/win16/i' => 'Windows 3.11',
	        '/macintosh|mac os x/i' => 'Mac OS X',
	        '/mac_powerpc/i' => 'Mac OS 9',
	        '/linux/i' => 'Linux',
	        '/ubuntu/i' => 'Ubuntu',
	        '/iphone/i' => 'iPhone',
	        '/ipod/i' => 'iPod',
	        '/ipad/i' => 'iPad',
	        '/android/i' => 'Android',
	        '/blackberry/i' => 'BlackBerry',
	        '/webos/i' => 'Mobile'
	    );
	    foreach ($osArray as $regex => $value) {
	        if (preg_match($regex, $userAgent)) {
	            $osPlatform = $value;
	        }
	    }
	    $browser = "Unknown Browser";
	    $browserArray = array(
	        '/msie/i' => 'Internet Explorer',
	        '/firefox/i' => 'Firefox',
	        '/safari/i' => 'Safari',
	        '/chrome/i' => 'Chrome',
	        '/edge/i' => 'Edge',
	        '/opera/i' => 'Opera',
	        '/netscape/i' => 'Netscape',
	        '/maxthon/i' => 'Maxthon',
	        '/konqueror/i' => 'Konqueror',
	        '/mobile/i' => 'Handheld Browser'
	    );
	    foreach ($browserArray as $regex => $value) {
	        if (preg_match($regex, $userAgent)) {
	            $browser = $value;
	        }
	    }

	    $data['os_platform'] = $osPlatform;
	    $data['browser'] = $browser;

	    return $data;
	}

}
