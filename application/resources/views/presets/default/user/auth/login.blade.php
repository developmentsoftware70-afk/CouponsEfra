@extends($activeTemplate.'layouts.frontend')

@section('content')
    <section class="login-section py-20">
        <div class="container-fluid container-custom">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="log-in-box wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                        <div class="login wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            <h2 class="welcome-text">@lang('Login Your Account')</h2>
                            <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                                @csrf
                                <div class="form-group pwow animate__animated animate__fadeInUp" data-wow-delay="0.3s">
                                    <input type="text" autocomplete="off" class="form--control mb-3" id="username" name="username" required placeholder="">
                                    <label class="form--label">@lang('Username or Email')</label>
                                </div>
                                <div class="form-group wow animate__animated animate__fadeInUp" data-wow-delay="0.3s">
                                    <input type="password" id="your-password" name="password"  autocomplete="off" placeholder=" " class="form--control mb-3 wow animate__animated animate__fadeInUp" data-wow-delay="0.4s" required>
                                    <label class="form--label">@lang('Password')</label>
                                </div>
                                <x-captcha></x-captcha>
                                <div class="login-meta mb-3 wow animate__animated animate__fadeInUp" data-wow-delay="0.5s">
                                    <div class="form--check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember" class="form-check-label">@lang('Remember me')</label>
                                    </div>
                                    <a class="text--base" href="{{ route('user.password.request') }}">@lang('Forgot Your Password')?</a>
                                    
                                </div>
                                <button class="btn btn--base wow animate__animated animate__fadeInUp" data-wow-delay="0.5s" style="width: 100%;">@lang('Login')</button>
                            </form>

                            <div class="mt-3 wow animate__animated animate__fadeInUp" data-wow-delay="0.6s">
                                <a href="{{ route('google.redirect') }}" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center" style="gap: 10px; border: 1px solid #ddd; padding: 10px;">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google logo" style="width: 20px;">
                                    @lang('Sign in with Google')
                                </a>
                            </div>
                            <p class="pt-3 wow animate__animated animate__fadeInUp" data-wow-delay="0.6s">@lang('Don\'t have any account?')  
                                <a href="{{ route('user.register') }}" class="text--base"> @lang('Create Account')</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection