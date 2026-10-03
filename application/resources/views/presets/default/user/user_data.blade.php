@extends($activeTemplate.'layouts.frontend')

@section('content')
<section class="signup-section py-20">
    <div class="container-fluid container-custom">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7 col-xl-6 log-in-box">
                <h3 class="itle wow animate__animated animate__fadeInUp" data-wow-delay="0.3s">{{ __($pageTitle) }}</h3>

                <form method="POST" action="{{ route('user.data.submit') }}">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-6 mb-3">
                            <input type="text" class="form--control" name="firstname"
                            value="{{ old('firstname') }}" placeholder="" required>
                            <label class="form--label">@lang('First Name')</label>
                        </div>

                        <div class="form-group col-sm-6 mb-3">
                            <input type="text" class="form--control" name="lastname"
                            value="{{ old('lastname') }}" placeholder="" required>
                            <label class="form--label">@lang('Last Name')</label>
                        </div>
                        <div class="form-group col-sm-6 mb-3">
                            <input type="text" class="form--control" name="address"
                            value="{{ old('address') }}" placeholder="">
                            <label class="form--label">@lang('Address')</label>
                        </div>
                        <div class="form-group col-sm-6 mb-3">
                            <input type="text" class="form--control" name="state"
                            value="{{ old('state') }}" placeholder="">
                            <label class="form--label">@lang('State')</label>
                        </div>
                        <div class="form-group col-sm-6 mb-3">
                            <input type="text" class="form--control" name="zip"
                            value="{{ old('zip') }}" placeholder="">
                            <label class="form--label">@lang('Zip Code')</label>
                        </div>

                        <div class="form-group col-sm-6 mb-3">
                            <input type="text" class="form--control" name="city"
                            value="{{ old('city') }}" placeholder="">
                            <label class="form--label">@lang('City')</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--base">
                            @lang('Submit')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection