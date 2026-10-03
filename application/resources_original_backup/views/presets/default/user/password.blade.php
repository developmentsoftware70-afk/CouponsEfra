@extends($activeTemplate.'layouts.master')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <form action="" method="post">
            @csrf
                <div class="row gy-4 justify-content-center">
                <div class="col-lg-6">
                    <div class="account-form"><div class="row gy-4">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label">@lang('Current Password')</label>
                                <input type="password" class="form-control form--control"  placeholder="@lang('Current Password')" name="current_password" required
                                    autocomplete="current-password">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label">@lang('Confirm Password')</label>
                                <input type="password" class="form-control form--control" placeholder="@lang('Confirm Password')" name="password_confirmation"
                                    required autocomplete="current-password">
                            </div>
                        </div>
                        <div class="col-lg-12"> 
                            <div class="form-group">
                                <label class="form-label">@lang('Password')</label>
                                <input type="password" class="form-control form--control" placeholder="@lang('Password')" name="password" required
                                    autocomplete="current-password">
                                @if($general->secure_password)
                                <div class="input-popup">
                                    <p class="error lower">@lang('1 small letter minimum')</p>
                                    <p class="error capital">@lang('1 capital letter minimum')</p>
                                    <p class="error number">@lang('1 number minimum')</p>
                                    <p class="error special">@lang('1 special character minimum')</p>
                                    <p class="error minimum">@lang('6 character password')</p>
                                </div>
                                @endif
                            </div>
                        </div> 
                        <div class="col-12 text-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn--base">@lang('Change Password')</button>
                            </div>
                        </div>
                    </div>
                        
                    </div>
                </div>
            </div>
        </form>
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