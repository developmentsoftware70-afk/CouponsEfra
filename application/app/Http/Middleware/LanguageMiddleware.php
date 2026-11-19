<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        session()->put('lang', $this->getCode());
        app()->setLocale(session('lang',  $this->getCode()));

        // Auto-accept GDPR cookie automatically for all requests
        if (!\Cookie::get('gdpr_cookie')) {
            \Cookie::queue('gdpr_cookie', 'accepted', 43200);
        }

        // Global Referral Tracking: capture ?ref= or ?reference= on any incoming page
        $ref = $request->query('ref') ?? $request->query('reference');
        if ($ref) {
            $cleanRef = trim($ref);
            session()->put('reference', $cleanRef);
            \Cookie::queue('reference', $cleanRef, 43200); // 30-day tracking cookie
        }

        return $next($request);
    }

    public function getCode()
    {
        if (session()->has('lang')) {
            return session('lang');
        }
        $language = Language::where('is_default', 1)->first();
        return $language ? $language->code : 'en';
    }
    
    
}
