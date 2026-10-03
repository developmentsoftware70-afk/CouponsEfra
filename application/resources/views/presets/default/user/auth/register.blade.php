@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
    $policyPages = getContent('policy_pages.element',false,null,true);
@endphp
<section class="signup-section py-20">
    <div class="container-fluid container-custom">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12">
                
                <div class="log-in-box">
                    <h3 class="welcome-text  wow animate__animated animate__fadeInUp mb-4" data-wow-delay="0.3s">@lang('Register')</h3>
                    <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha">
                        @csrf 
                            <div class="row mb-4 wow animate__animated animate__fadeInUp" data-wow-delay="0.4s">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <input type="text" class="form--control checkUser" name="username" value="{{ old('username') }}" placeholder="" required>
                                        <label class="form--label">@lang('Username')</label>
                                        <small class="text-danger usernameExist"></small>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <input type="email" class="form--control checkUser" name="email" value="{{ old('email') }}" placeholder="" required>
                                        <label class="form--label">@lang('E-Mail Address')</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <select name="country" class="form--control">
                                            @foreach($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}</option>
                                            @endforeach
                                        </select>
                                        <label class="form--label">@lang('Country')</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <div class="input-group ">
                                            <span class="input-group-text mobile-code"> </span>
                                            <input type="hidden" name="mobile_code">
                                            <input type="hidden" name="country_code">
                                            <input type="number" name="mobile" value="{{ old('mobile') }}" class="form-control form--control checkUser" placeholder="@lang('Mobile')" required>
                                        </div>
                                        <small class="text-danger mobileExist"></small>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <input type="password" class="form--control" name="password" placeholder="" required>
                                        <label class="form--label">@lang('Password')</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <input type="password" class="form--control" name="password_confirmation" placeholder="" required>
                                        <label class="form--label">@lang('Confirm Password')</label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <select name="signup_type" class="form--control" required>
                                            <option value="">Select signup type</option>
                                            <option value="1" {{ old('signup_type') == '1' ? 'selected' : '' }}>Signup as a user</option>
                                            <option value="2" {{ old('signup_type') == '2' ? 'selected' : '' }}>Signup as an affiliate</option>
                                        </select>
                                        <label class="form--label">@lang('Signup Type')</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Affiliate fields (conditionally shown) -->
                            <div id="affiliate-fields" class="row mb-4 d-none">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <input type="text" name="organization_name" class="form--control" value="{{ old('organization_name') }}" placeholder="" required>
                                        <label class="form--label">@lang('Organization Name')</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <input type="text" name="traffic_source" class="form--control" value="{{ old('traffic_source') }}" placeholder="" required>
                                        <label class="form--label">@lang('Source of Traffic')</label>
                                    </div>
                                </div>
                            </div>

                            <x-captcha></x-captcha>

                        @if($general->agree)
                        <div class="form--check mb-3">
                            <input class="form-check-input" type="checkbox" id="agree" @checked(old('agree')) name="agree" required>
                            <label class="form-check-label" for="agree">@lang('I agree with') @foreach($policyPages as $policy) <a class="text--base" href="{{ route('policy.pages',[slug($policy->data_values->title),$policy->id]) }}">{{ __($policy->data_values->title) }}</a> @if(!$loop->last), @endif @endforeach</label>
                        </div>
                        @endif
                        <div class="form-group mb-3">
                            <button type="submit" id="recaptcha" class="btn btn--base"> @lang('Register')</button>
                        </div>
                        <p class="mb-0">@lang('Already have an account?') <a class="text--base" target="_blank" href="{{ route('user.login') }}">@lang('Login')</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
        <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <i class="las la-times"></i>
        </span>
      </div>
      <div class="modal-body">
        <h6 class="text-center">@lang('You already have an account please Login ')</h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
        <a href="{{ route('user.login') }}" class="btn btn--base btn-sm">@lang('Login')</a>
      </div>
    </div>
  </div>
</div>
@endsection
@push('style')
<style>
    .country-code .input-group-text{
        background: #fff !important;
    }
    .country-code select{
        border: none;
    }
    .country-code select:focus{
        border: none;
        outline: none;
    }
</style>
@endpush
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const signupType = document.querySelector('select[name="signup_type"]');
        const affiliateFields = document.getElementById('affiliate-fields');

        if (!signupType || !affiliateFields) return;

        function toggleAffiliateFields() {
            const isAffiliate = signupType.value === '2';

            affiliateFields.classList.toggle('d-none', !isAffiliate);
            affiliateFields.querySelectorAll('input').forEach(input => {
                input.required = isAffiliate;
            });
        }

        signupType.addEventListener('change', toggleAffiliateFields);
        toggleAffiliateFields(); // Run on load to apply old input logic
    });

    </script>
    <script>
      "use strict";
        (function ($) {
            @if($mobileCode)
            $(`option[data-code={{ $mobileCode }}]`).attr('selected','');
            @endif

            $('select[name=country]').change(function(){
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));

            $('.checkUser').on('focusout',function(e){
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {mobile:mobile,_token:token}
                }
                if ($(this).attr('name') == 'email') {
                    var data = {email:value,_token:token}
                }
                /*
                if ($(this).attr('name') == 'signup_type') {
                    var data = {signup_type:value,_token:token}
                }
                if ($(this).attr('name') == 'organization_name') {
                    var data = {organization_name:value,_token:token}
                }
                if ($(this).attr('name') == 'traffic_source') {
                    var data = {traffic_source:value,_token:token}
                }
                */
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
