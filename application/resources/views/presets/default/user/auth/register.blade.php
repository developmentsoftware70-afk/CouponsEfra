@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
    $policyPages = getContent('policy_pages.element',false,null,true);
@endphp
<style>
/* ========================================================
   BIG TECH COMPANY AUTH DESIGN - REGISTER PAGE
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
    max-width: 640px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.06), 0 20px 25px -5px rgba(0, 0, 0, 0.03);
    padding: 44px 42px;
    position: relative;
    z-index: 5;
    transition: all 0.3s ease;
}

@media (max-width: 576px) {
    .corp-auth-card {
        padding: 32px 22px;
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

/* Form Controls */
.corp-form-group {
    margin-bottom: 16px;
}

.corp-form-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 5px;
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
    font-size: 14px;
    pointer-events: none;
    transition: color 0.2s ease;
    z-index: 2;
}

.corp-input-wrap input.corp-input,
.corp-input-wrap select.corp-input {
    width: 100%;
    height: 46px;
    padding: 0 14px 0 40px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 11px;
    font-size: 14px;
    color: #0f172a;
    outline: none;
    transition: all 0.2s ease;
}

.corp-input-wrap input.corp-input:focus,
.corp-input-wrap select.corp-input:focus {
    border-color: #c30009;
    box-shadow: 0 0 0 3px rgba(195, 0, 9, 0.12);
}

.corp-input-wrap input.corp-input:focus ~ .field-icon,
.corp-input-wrap:focus-within .field-icon {
    color: #c30009;
}

/* Mobile Phone Group with Dial Code Badge */
.corp-phone-group {
    display: flex;
    align-items: center;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 11px;
    height: 46px;
    overflow: hidden;
    transition: all 0.2s ease;
    width: 100%;
}

.corp-phone-group:focus-within {
    border-color: #c30009;
    box-shadow: 0 0 0 3px rgba(195, 0, 9, 0.12);
}

.corp-phone-badge {
    background: #f1f5f9;
    color: #334155;
    font-size: 14px;
    font-weight: 600;
    padding: 0 14px;
    height: 100%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-right: 1px solid #cbd5e1;
    user-select: none;
    flex-shrink: 0;
    letter-spacing: 0.3px;
}

.corp-phone-input {
    flex-grow: 1;
    height: 100%;
    border: none;
    background: transparent;
    padding: 0 14px;
    font-size: 14px;
    color: #0f172a;
    outline: none;
}

.corp-phone-input::placeholder {
    color: #94a3b8;
    font-size: 14px;
}

.corp-toggle-pwd {
    position: absolute;
    right: 12px;
    background: transparent;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 6px;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s;
    z-index: 3;
}

.corp-toggle-pwd:hover {
    color: #334155;
}

/* Affiliate Fields Box */
#affiliate-fields {
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 12px;
    padding: 16px 14px 4px 14px;
    margin-bottom: 16px;
}

/* Terms Checkbox */
.corp-terms-row {
    font-size: 13px;
    color: #475569;
    margin-bottom: 22px;
    display: flex;
    align-items: flex-start;
    gap: 8px;
}

.corp-terms-row input[type="checkbox"] {
    margin-top: 3px;
    width: 16px;
    height: 16px;
    accent-color: #c30009;
    flex-shrink: 0;
    cursor: pointer;
}

.corp-terms-row a {
    color: #c30009;
    font-weight: 600;
    text-decoration: none;
}

.corp-terms-row a:hover {
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
        
        <!-- Header & Logo -->
        <div class="corp-auth-header">
            <a href="{{ route('home') }}" class="corp-brand-logo">
                <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="{{ config('app.name') }}">
            </a>
            <h1 class="corp-auth-title">@lang('Register')</h1>
        </div>

        <!-- Google One-Click Login -->
        <a href="{{ route('google.redirect') }}" class="corp-google-btn">
            <svg width="20" height="20" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.36 24 12 24z"/>
                <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.36 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
            </svg>
            <span>@lang('Continue with Google')</span>
        </a>

        <!-- Divider -->
        <div class="corp-divider">
            <span>@lang('or register with email')</span>
        </div>

        <!-- Registration Form -->
        <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha">
            @csrf 

            <div class="row">
                <!-- Username -->
                <div class="col-md-6 corp-form-group">
                    <label for="reg-username" class="corp-form-label">@lang('Username')</label>
                    <div class="corp-input-wrap">
                        <i class="far fa-user field-icon"></i>
                        <input type="text" 
                               id="reg-username"
                               class="corp-input checkUser" 
                               name="username" 
                               value="{{ old('username') }}" 
                               placeholder="@lang('Username')" 
                               required>
                    </div>
                    <small class="text-danger usernameExist" style="font-size: 12px;"></small>
                </div>

                <!-- Email -->
                <div class="col-md-6 corp-form-group">
                    <label for="reg-email" class="corp-form-label">@lang('E-Mail Address')</label>
                    <div class="corp-input-wrap">
                        <i class="far fa-envelope field-icon"></i>
                        <input type="email" 
                               id="reg-email"
                               class="corp-input checkUser" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="@lang('E-Mail Address')" 
                               required>
                    </div>
                    <small class="text-danger emailExist" style="font-size: 12px;"></small>
                </div>

                <!-- Country -->
                <div class="col-md-6 corp-form-group">
                    <label for="reg-country" class="corp-form-label">@lang('Country')</label>
                    <div class="corp-input-wrap">
                        <i class="fas fa-globe-americas field-icon"></i>
                        <select name="country" id="reg-country" class="corp-input">
                            @foreach($countries as $key => $country)
                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}" @selected($key == ($mobileCode ?: 'IN'))>
                                {{ __($country->country) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Mobile Number -->
                <div class="col-md-6 corp-form-group">
                    <label for="reg-mobile" class="corp-form-label">@lang('Mobile Number')</label>
                    <div class="corp-phone-group">
                        <span class="corp-phone-badge mobile-code">+91</span>
                        <input type="hidden" name="mobile_code" value="91">
                        <input type="hidden" name="country_code" value="IN">
                        <input type="number" 
                               id="reg-mobile"
                               name="mobile" 
                               value="{{ old('mobile') }}" 
                               class="corp-phone-input checkUser" 
                               placeholder="@lang('Mobile')" 
                               required>
                    </div>
                    <small class="text-danger mobileExist" style="font-size: 12px;"></small>
                </div>

                <!-- Password -->
                <div class="col-md-6 corp-form-group">
                    <label for="reg-password" class="corp-form-label">@lang('Password')</label>
                    <div class="corp-input-wrap">
                        <i class="far fa-lock field-icon"></i>
                        <input type="password" 
                               id="reg-password"
                               class="corp-input" 
                               name="password" 
                               placeholder="@lang('Password')" 
                               required>
                        <button type="button" class="corp-toggle-pwd" id="toggleRegPasswordBtn" aria-label="Toggle password">
                            <i class="far fa-eye" id="toggleRegPasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6 corp-form-group">
                    <label for="reg-confirm-password" class="corp-form-label">@lang('Confirm Password')</label>
                    <div class="corp-input-wrap">
                        <i class="far fa-lock-alt field-icon"></i>
                        <input type="password" 
                               id="reg-confirm-password"
                               class="corp-input" 
                               name="password_confirmation" 
                               placeholder="@lang('Confirm Password')" 
                               required>
                        <button type="button" class="corp-toggle-pwd" id="toggleConfirmPasswordBtn" aria-label="Toggle confirm password">
                            <i class="far fa-eye" id="toggleConfirmPasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Signup Type -->
                <div class="col-md-12 corp-form-group">
                    <label for="reg-signup-type" class="corp-form-label">@lang('Signup Type')</label>
                    <div class="corp-input-wrap">
                        <i class="far fa-id-badge field-icon"></i>
                        <select name="signup_type" id="reg-signup-type" class="corp-input" required>
                            <option value="">@lang('Select signup type')</option>
                            <option value="1" {{ old('signup_type') == '1' ? 'selected' : '' }}>@lang('Signup as a user')</option>
                            <option value="2" {{ old('signup_type') == '2' ? 'selected' : '' }}>@lang('Signup as an affiliate')</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Affiliate fields (conditionally shown) -->
            <div id="affiliate-fields" class="row d-none">
                <div class="col-md-6 corp-form-group">
                    <label class="corp-form-label">@lang('Organization Name')</label>
                    <div class="corp-input-wrap">
                        <i class="far fa-building field-icon"></i>
                        <input type="text" name="organization_name" class="corp-input" value="{{ old('organization_name') }}" placeholder="@lang('Organization Name')">
                    </div>
                </div>
                <div class="col-md-6 corp-form-group">
                    <label class="corp-form-label">@lang('Source of Traffic')</label>
                    <div class="corp-input-wrap">
                        <i class="far fa-chart-line field-icon"></i>
                        <input type="text" name="traffic_source" class="corp-input" value="{{ old('traffic_source') }}" placeholder="@lang('Source of Traffic')">
                    </div>
                </div>
            </div>

            <x-captcha></x-captcha>

            <!-- Agree Terms -->
            @if($general->agree)
            <div class="corp-terms-row">
                <input type="checkbox" id="agree" @checked(old('agree')) name="agree" required>
                <label for="agree" class="mb-0">
                    @lang('I agree with') 
                    @foreach($policyPages as $policy) 
                        <a href="{{ route('policy.pages',[slug($policy->data_values->title),$policy->id]) }}">{{ __($policy->data_values->title) }}</a>@if(!$loop->last), @endif 
                    @endforeach
                </label>
            </div>
            @endif

            <!-- Register Submit -->
            <button type="submit" id="recaptcha" class="corp-submit-btn">
                <span>@lang('Register')</span>
                <i class="fas fa-arrow-right"></i>
            </button>

            <!-- Switch Link -->
            <p class="corp-switch-prompt">
                @lang('Already have an account?') 
                <a href="{{ route('user.login') }}">@lang('Login')</a>
            </p>

            <!-- Trust Note -->
            <div class="corp-trust-badge">
                <i class="fas fa-shield-alt text-success"></i>
                <span>@lang('Protected by 256-bit SSL encryption')</span>
            </div>
        </form>
    </div>
</section>

<!-- Exist Modal -->
<div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
      <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 18px 24px;">
        <h5 class="modal-title fw-bold" id="existModalLongTitle" style="color: #0f172a; font-size: 17px;">
            <i class="fas fa-info-circle text-primary me-2"></i>@lang('You are with us')
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4 px-4">
        <h6 class="fw-bold mb-0 text-center" style="color: #0f172a;">@lang('You already have an account please Login ')</h6>
      </div>
      <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 24px;">
        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">@lang('Close')</button>
        <a href="{{ route('user.login') }}" class="btn btn--base btn-sm px-4">@lang('Login')</a>
      </div>
    </div>
  </div>
</div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Password Visibility Toggles
            function setupToggle(btnId, inputId, iconId) {
                const btn = document.getElementById(btnId);
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                if (btn && input && icon) {
                    btn.addEventListener('click', function () {
                        const isPassword = input.getAttribute('type') === 'password';
                        input.setAttribute('type', isPassword ? 'text' : 'password');
                        icon.classList.toggle('fa-eye', !isPassword);
                        icon.classList.toggle('fa-eye-slash', isPassword);
                    });
                }
            }

            setupToggle('toggleRegPasswordBtn', 'reg-password', 'toggleRegPasswordIcon');
            setupToggle('toggleConfirmPasswordBtn', 'reg-confirm-password', 'toggleConfirmPasswordIcon');

            // Affiliate fields toggle
            const signupType = document.querySelector('select[name="signup_type"]');
            const affiliateFields = document.getElementById('affiliate-fields');

            if (signupType && affiliateFields) {
                function toggleAffiliateFields() {
                    const isAffiliate = signupType.value === '2';
                    affiliateFields.classList.toggle('d-none', !isAffiliate);
                    affiliateFields.querySelectorAll('input').forEach(input => {
                        input.required = isAffiliate;
                    });
                }

                signupType.addEventListener('change', toggleAffiliateFields);
                toggleAffiliateFields();
            }
        });
    </script>
    <script>
      "use strict";
        (function ($) {
            var defaultCountryCode = '{{ $mobileCode ?: "IN" }}';
            var defaultOption = $(`option[data-code=${defaultCountryCode}]`);
            if (defaultOption.length) {
                defaultOption.prop('selected', true);
                $('select[name=country]').val(defaultOption.val());
            }

            function updateMobileCode() {
                var sel = $('select[name=country] :selected');
                var mCode = sel.data('mobile_code') || '91';
                var cCode = sel.data('code') || 'IN';
                $('input[name=mobile_code]').val(mCode);
                $('input[name=country_code]').val(cCode);
                $('.mobile-code').text('+' + mCode);
            }

            $('select[name=country]').on('change', updateMobileCode);
            updateMobileCode();

            $('.checkUser').on('focusout',function(e){
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mCode = $('input[name=mobile_code]').val() || '91';
                    var mobile = `${mCode}${value}`;
                    var data = {mobile:mobile,_token:token}
                }
                if ($(this).attr('name') == 'email') {
                    var data = {email:value,_token:token}
                }
                if ($(this).attr('name') == 'username') {
                    var data = {username:value,_token:token}
                }
                $.post(url,data,function(response) {
                  if (response.data != false && response.type == 'email') {
                    $('#existModalCenter').modal('show');
                  }else if(response.data != false){
                    $(`.${response.type}Exist`).text(`${response.type} already exist`);
                  }else{
                    $(`.${response.type}Exist`).text('');
                  }
                });
            });
        })(jQuery);
    </script>
@endpush
