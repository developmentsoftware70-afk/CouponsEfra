@extends($activeTemplate.'layouts.frontend')

@section('content')
<style>
/* ========================================================
   MODERN AUTHENTICATION PAGE - CLEAN CARD DESIGN
   ======================================================== */

/* Hide the large banner breadcrumb on the dedicated login page */
.breadcumb {
    display: none !important;
}

/* Page Container */
.login-page-section {
    min-height: calc(100vh - 120px);
    background-color: #f8fafc;
    background-image: 
        radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.04) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(239, 68, 68, 0.04) 0px, transparent 50%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 15px;
    position: relative;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* Auth Card */
.auth-card-box {
    background: #ffffff;
    width: 100%;
    max-width: 440px;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.06), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
    padding: 38px 34px;
    position: relative;
    z-index: 5;
    transition: box-shadow 0.3s ease;
}

@media (max-width: 480px) {
    .auth-card-box {
        padding: 28px 20px;
        border-radius: 14px;
    }
}

/* Header inside Card */
.auth-header {
    text-align: center;
    margin-bottom: 28px;
}

.auth-badge-icon {
    width: 52px;
    height: 52px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #0f172a;
    font-size: 20px;
    margin-bottom: 14px;
}

.auth-title {
    font-size: 24px;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.3px;
    margin-bottom: 6px;
}

.auth-subtitle {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 0;
    line-height: 1.5;
}

/* Form Groups */
.auth-field-group {
    margin-bottom: 18px;
    position: relative;
}

.auth-field-label {
    display: block;
    font-size: 13.5px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
}

.auth-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.auth-input-icon {
    position: absolute;
    left: 14px;
    color: #94a3b8;
    font-size: 15px;
    pointer-events: none;
    transition: color 0.2s ease;
}

.auth-text-input {
    width: 100%;
    height: 46px;
    padding: 0 14px 0 42px;
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    font-size: 14.5px;
    color: #0f172a;
    outline: none;
    transition: all 0.2s ease;
}

.auth-text-input:focus {
    background: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.auth-text-input:focus ~ .auth-input-icon,
.auth-input-wrapper:focus-within .auth-input-icon {
    color: #2563eb;
}

.auth-text-input::placeholder {
    color: #94a3b8;
    font-size: 13.5px;
}

/* Password Toggle */
.auth-password-toggle {
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

.auth-password-toggle:hover {
    color: #334155;
}

/* Remember & Forgot Row */
.auth-options-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 4px;
    margin-bottom: 22px;
    font-size: 13.5px;
}

.auth-checkbox-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #475569;
    cursor: pointer;
    user-select: none;
    font-size: 13.5px;
    margin-bottom: 0;
}

.auth-checkbox-label input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #2563eb;
    cursor: pointer;
    border-radius: 4px;
}

.auth-forgot-link {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.auth-forgot-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

/* Submit Button */
.auth-primary-btn {
    width: 100%;
    height: 48px;
    background: #0f172a;
    color: #ffffff;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 0.2px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
}

.auth-primary-btn:hover {
    background: #1e293b;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(15, 23, 42, 0.22);
}

.auth-primary-btn:active {
    transform: translateY(0);
}

/* Divider */
.auth-or-divider {
    position: relative;
    text-align: center;
    margin: 22px 0;
}

.auth-or-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e2e8f0;
}

.auth-or-divider span {
    position: relative;
    background: #ffffff;
    padding: 0 14px;
    font-size: 12.5px;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

/* Google Social Button */
.auth-google-btn {
    width: 100%;
    height: 46px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 14.5px;
    font-weight: 500;
    color: #334155;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
}

.auth-google-btn:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a;
}

.auth-google-btn svg {
    flex-shrink: 0;
}

/* Switch Page Text */
.auth-footer-prompt {
    text-align: center;
    margin-top: 24px;
    font-size: 14px;
    color: #64748b;
}

.auth-footer-prompt a {
    color: #2563eb;
    font-weight: 600;
    text-decoration: none;
}

.auth-footer-prompt a:hover {
    text-decoration: underline;
}

/* Trust Badge */
.auth-trust-note {
    text-align: center;
    margin-top: 18px;
    font-size: 12px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
</style>

<section class="login-page-section">
    <div class="auth-card-box wow animate__animated animate__fadeInUp" data-wow-delay="0.15s">
        
        <!-- Header -->
        <div class="auth-header">
            <div class="auth-badge-icon">
                <i class="fas fa-user-lock"></i>
            </div>
            <h1 class="auth-title">@lang('Welcome Back')</h1>
            <p class="auth-subtitle">@lang('Sign in to access your coupons, cashback & earnings')</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
            @csrf

            <!-- Username or Email -->
            <div class="auth-field-group">
                <label for="username" class="auth-field-label">@lang('Username or Email')</label>
                <div class="auth-input-wrapper">
                    <i class="far fa-envelope auth-input-icon"></i>
                    <input type="text" 
                           class="auth-text-input" 
                           id="username" 
                           name="username" 
                           value="{{ old('username') }}" 
                           required 
                           autocomplete="username" 
                           placeholder="@lang('Enter username or email')">
                </div>
            </div>

            <!-- Password -->
            <div class="auth-field-group">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="your-password" class="auth-field-label mb-0">@lang('Password')</label>
                </div>
                <div class="auth-input-wrapper">
                    <i class="far fa-lock auth-input-icon"></i>
                    <input type="password" 
                           class="auth-text-input" 
                           id="your-password" 
                           name="password" 
                           required 
                           autocomplete="current-password" 
                           placeholder="@lang('Enter your password')">
                    <button type="button" class="auth-password-toggle" id="togglePasswordBtn" aria-label="Toggle password visibility">
                        <i class="far fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <x-captcha></x-captcha>

            <!-- Remember me & Forgot Password -->
            <div class="auth-options-row">
                <label class="auth-checkbox-label">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>@lang('Remember me')</span>
                </label>
                <a href="{{ route('user.password.request') }}" class="auth-forgot-link">@lang('Forgot Password?')</a>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="auth-primary-btn">
                <span>@lang('Sign In')</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <!-- Divider -->
        <div class="auth-or-divider">
            <span>@lang('or continue with')</span>
        </div>

        <!-- Google Login -->
        <a href="{{ route('google.redirect') }}" class="auth-google-btn">
            <svg width="18" height="18" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.36 24 12 24z"/>
                <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.36 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
            </svg>
            <span>@lang('Sign in with Google')</span>
        </a>

        <!-- Switch Link -->
        <p class="auth-footer-prompt">
            @lang('Don\'t have an account?') 
            <a href="{{ route('user.register') }}">@lang('Create Account')</a>
        </p>

        <!-- Trust Note -->
        <div class="auth-trust-note">
            <i class="fas fa-shield-alt text-success"></i>
            <span>@lang('256-bit Secure Encryption')</span>
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