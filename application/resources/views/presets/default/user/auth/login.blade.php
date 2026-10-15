@extends($activeTemplate.'layouts.frontend')

@section('content')
<style>
/* ========================================================
   BIG TECH COMPANY AUTH DESIGN - CLEAN, HIGH-TRUST & MODERN
   ======================================================== */
.breadcumb {
    display: none !important;
}

.corp-auth-section {
    min-height: calc(100vh - 120px);
    background-color: #f8fafc;
    background-image: 
        radial-gradient(circle at 10% 20%, rgba(195, 0, 9, 0.03) 0%, transparent 40%),
        radial-gradient(circle at 90% 80%, rgba(37, 99, 235, 0.03) 0%, transparent 40%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.corp-auth-card {
    width: 100%;
    max-width: 460px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.06), 0 20px 25px -5px rgba(0, 0, 0, 0.03);
    padding: 44px 40px;
    position: relative;
    z-index: 5;
    transition: all 0.3s ease;
}

@media (max-width: 480px) {
    .corp-auth-card {
        padding: 32px 24px;
        border-radius: 16px;
    }
}

/* Header & Logo */
.corp-auth-header {
    text-align: center;
    margin-bottom: 26px;
}

.corp-brand-logo {
    display: inline-block;
    margin-bottom: 16px;
}

.corp-brand-logo img {
    height: 48px;
    width: auto;
    object-fit: contain;
}

.corp-auth-title {
    font-size: 24px;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.4px;
    margin-bottom: 6px;
}

.corp-auth-sub {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 0;
    line-height: 1.5;
}

/* Google Sign-in */
.corp-google-btn {
    width: 100%;
    height: 48px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    font-size: 14.5px;
    font-weight: 600;
    color: #334155;
    text-decoration: none;
    transition: all 0.25s ease;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
}

.corp-google-btn:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    transform: translateY(-1px);
}

/* Divider */
.corp-divider {
    position: relative;
    text-align: center;
    margin: 22px 0;
}

.corp-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e2e8f0;
}

.corp-divider span {
    position: relative;
    background: #ffffff;
    padding: 0 14px;
    font-size: 12.5px;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

/* Form Groups */
.corp-form-group {
    margin-bottom: 18px;
}

.corp-form-label {
    display: block;
    font-size: 13.5px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
}

.corp-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.corp-input-wrap .field-icon {
    position: absolute;
    left: 14px;
    color: #94a3b8;
    font-size: 15px;
    pointer-events: none;
    transition: color 0.2s ease;
}

.corp-input-wrap input.corp-input {
    width: 100%;
    height: 48px;
    padding: 0 14px 0 42px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    font-size: 14.5px;
    color: #0f172a;
    outline: none;
    transition: all 0.2s ease;
}

.corp-input-wrap input.corp-input:focus {
    border-color: #c30009;
    box-shadow: 0 0 0 3px rgba(195, 0, 9, 0.12);
}

.corp-input-wrap input.corp-input:focus ~ .field-icon,
.corp-input-wrap:focus-within .field-icon {
    color: #c30009;
}

.corp-toggle-pwd {
    position: absolute;
    right: 12px;
    background: transparent;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 6px;
    font-size: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s;
}

.corp-toggle-pwd:hover {
    color: #334155;
}

/* Options Row */
.corp-options-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 4px;
    margin-bottom: 22px;
    font-size: 13.5px;
}

.corp-checkbox-wrap {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #475569;
    cursor: pointer;
    user-select: none;
    font-size: 13.5px;
    margin-bottom: 0;
}

.corp-checkbox-wrap input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #c30009;
    cursor: pointer;
    border-radius: 4px;
}

.corp-forgot-link {
    color: #c30009;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}

.corp-forgot-link:hover {
    color: #990007;
    text-decoration: underline;
}

/* Submit CTA */
.corp-submit-btn {
    width: 100%;
    height: 50px;
    background: linear-gradient(135deg, #c30009 0%, #e11d48 100%);
    color: #ffffff;
    border: none;
    border-radius: 12px;
    font-size: 15.5px;
    font-weight: 600;
    letter-spacing: 0.2px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 4px 14px rgba(195, 0, 9, 0.3);
}

.corp-submit-btn:hover {
    background: linear-gradient(135deg, #b00008 0%, #be123c 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(195, 0, 9, 0.38);
}

.corp-submit-btn:active {
    transform: translateY(0);
}

/* Switch & Trust */
.corp-switch-prompt {
    text-align: center;
    margin-top: 22px;
    font-size: 14px;
    color: #64748b;
}

.corp-switch-prompt a {
    color: #c30009;
    font-weight: 600;
    text-decoration: none;
}

.corp-switch-prompt a:hover {
    text-decoration: underline;
}

.corp-trust-badge {
    text-align: center;
    margin-top: 18px;
    font-size: 12px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
</style>

<section class="corp-auth-section">
    <div class="corp-auth-card wow animate__animated animate__fadeInUp" data-wow-delay="0.15s">
        
        <!-- Header & Brand Logo -->
        <div class="corp-auth-header">
            <a href="{{ route('home') }}" class="corp-brand-logo">
                <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="{{ config('app.name') }}">
            </a>
            <h1 class="corp-auth-title">@lang('Login Your Account')</h1>
        </div>

        <!-- Google One-Click Login -->
        <a href="{{ route('google.redirect') }}" class="corp-google-btn">
            <svg width="20" height="20" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.36 24 12 24z"/>
                <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.36 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
            </svg>
            <span>@lang('Sign in with Google')</span>
        </a>

        <!-- Divider -->
        <div class="corp-divider">
            <span>@lang('or sign in with email')</span>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
            @csrf

            <!-- Username or Email -->
            <div class="corp-form-group">
                <label for="username" class="corp-form-label">@lang('Username or Email')</label>
                <div class="corp-input-wrap">
                    <i class="far fa-envelope field-icon"></i>
                    <input type="text" 
                           class="corp-input" 
                           id="username" 
                           name="username" 
                           value="{{ old('username') }}" 
                           required 
                           autocomplete="username" 
                           placeholder="@lang('Username or Email')">
                </div>
            </div>

            <!-- Password -->
            <div class="corp-form-group">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="your-password" class="corp-form-label mb-0">@lang('Password')</label>
                </div>
                <div class="corp-input-wrap">
                    <i class="far fa-lock field-icon"></i>
                    <input type="password" 
                           class="corp-input" 
                           id="your-password" 
                           name="password" 
                           required 
                           autocomplete="current-password" 
                           placeholder="@lang('Password')">
                    <button type="button" class="corp-toggle-pwd" id="togglePasswordBtn" aria-label="Toggle password visibility">
                        <i class="far fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <x-captcha></x-captcha>

            <!-- Remember & Forgot -->
            <div class="corp-options-row">
                <label class="corp-checkbox-wrap">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>@lang('Remember me')</span>
                </label>
                <a href="{{ route('user.password.request') }}" class="corp-forgot-link">@lang('Forgot Your Password')?</a>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="corp-submit-btn">
                <span>@lang('Login')</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <!-- Switch to Register -->
        <p class="corp-switch-prompt">
            @lang("Don't have any account?") 
            <a href="{{ route('user.register') }}">@lang('Create Account')</a>
        </p>

        <!-- Trust Note -->
        <div class="corp-trust-badge">
            <i class="fas fa-shield-alt text-success"></i>
            <span>@lang('Protected by 256-bit SSL encryption')</span>
        </div>
    </div>
</section>

@push('script')
<script>
    (function () {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('your-password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.classList.toggle('fa-eye', !isPassword);
                toggleIcon.classList.toggle('fa-eye-slash', isPassword);
            });
        }
    })();
</script>
@endpush
@endsection