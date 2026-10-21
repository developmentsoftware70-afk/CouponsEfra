<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    // Redirect user to Google
    public function redirect()
    {
        if (!session()->has('url.intended')) {
            $prev = url()->previous();
            if ($prev && $prev != url()->current() && !str_contains($prev, '/user/login') && !str_contains($prev, '/user/register')) {
                session()->put('url.intended', $prev);
            }
        }
        return Socialite::driver('google')->stateless()->with(['prompt' => 'select_account'])->redirect();
    }

    // Handle Google callback
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'username' => Str::slug($googleUser->getName()) . rand(100, 999),
                    'firstname' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(24)),
                    'signup_type' => 1,
                    'status' => 1,
                    'ev' => 1,
                    'sv' => 1,
                ]);
            } else if (!$user->provider_id) {
                // Link Google to existing account
                $user->update([
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                ]);
            }

            // Using Auth::login to persist session for the Blade application 
            // instead of redirect()->away with tokens.
            Auth::login($user);
            \Cookie::queue('couponsefra_logged_in', '1', 60 * 24 * 30, '/', null, false, false);

            $intendedUrl = session()->pull('url.intended');
            if ($intendedUrl) {
                return redirect()->to($intendedUrl);
            }

            return redirect()->intended(route('home'));

        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('user.login')->with('error', 'Google login failed. Please try again.');
        }
    }
}
