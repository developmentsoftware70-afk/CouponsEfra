@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="container-fluid container-custom py-20">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7 col-xl-5">

            <div class="log-in-box">
    
                <h4 class="card-title">@lang('Reset Password')</h4>
            
                <div class="mb-4">
                    <p>@lang('Your account is verified successfully. Now you can change your password. Please enter
                        a strong password and don\'t share it with anyone.')</p>
                </div>
                <form method="POST" action="{{ route('user.password.update') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group mb-3">
                        <input type="password" class="form--control form--control" name="password" required placeholder="">
                        <label class="form--label">@lang('Password')</label>
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" class="form--control form--control" name="password_confirmation"
                            required placeholder="">
                        <label class="form--label">@lang('Confirm Password')</label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--base w-100"> @lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-lib')
<script src="{{ asset('assets/common/js/secure_password.js') }}"></script>
@endpush
@push('script')
<script>
    (function ($) {
        "use strict";
        @if ($general -> secure_password)
            $('input[name=password]').on('input', function () {
                secure_password($(this));
            });

        $('[name=password]').focus(function () {
            $(this).closest('.form-group').addClass('hover-input-popup');
        });

        $('[name=password]').focusout(function () {
            $(this).closest('.form-group').removeClass('hover-input-popup');
        });
        @endif
    })(jQuery);
</script>
@endpush