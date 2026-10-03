@extends($activeTemplate.'layouts.master')
@section('content')
<div class="row justify-content-center gy-4">
    @if(!auth()->user()->ts)
    <div class="col-md-6">
        <div class="account-form">
            <h5 class="card-title mb-4">@lang('Add Your Account')</h5>
            <h6 class="mb-3">
                @lang('Use the QR code or setup key on your Google Authenticator app to add your account. ')
            </h6>

            <div class="form-group mx-auto text-center">
                <img class="mx-auto" src="{{$qrCodeUrl}}">
            </div>

            <div class="form-group">
                <label class="form-label">@lang('Setup Key')</label>
                <div class="input-group">
                    <input type="text" name="key" value="{{$secret}}"
                        class="form-control form--control referralURL" readonly>
                    <button type="button" class="input-group-text copytext" id="copyBoard"> <i
                            class="fa fa-copy"></i> </button>
                </div>
            </div>
        </div>
    </div>

    @endif

    <div class="col-md-6">

        @if(auth()->user()->ts)
        <div class="account-form">
            <form action="{{route('user.twofactor.disable')}}" method="POST">
                <h5 class="card-title mb-4">@lang('Disable 2FA Security')</h5>
                @csrf
                <input type="hidden" name="key" value="{{$secret}}">
                <div class="form-group">
                    <label class="form-label">@lang('Google Authenticatior OTP')</label>
                    <input type="text" class="form-control form--control" name="code" required>
                </div>
                <button type="submit" class="btn btn--base w-100">@lang('Save')</button>
            </form>
        </div>
        @else
        <div class="account-form">
            <form action="{{ route('user.twofactor.enable') }}" method="POST">
                <h5 class="card-title mb-4">@lang('Enable 2FA Security')</h5>
                @csrf
                <input type="hidden" name="key" value="{{$secret}}">
                <div class="form-group">
                    <label class="form-label">@lang('Google Authenticatior OTP')</label>
                    <input type="text" class="form-control form--control" name="code" required>
                </div>
                <button type="submit" class="btn btn--base mt-4">@lang('Submit')</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection

@push('style')
<style>
    .copied::after {
        background-color: #{{ $general->base_color }
    };
    }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        $('#copyBoard').on('click', function () {
            var copyText = document.getElementsByClassName("referralURL");
            copyText = copyText[0];
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            copyText.blur();
            this.classList.add('copied');
            setTimeout(() => this.classList.remove('copied'), 1500);
        });
    })(jQuery);
</script>
@endpush