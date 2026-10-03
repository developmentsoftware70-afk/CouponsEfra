@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card p-2">
                        <ul class="d-flex flex-wrap gap-1">
                        

                            <li class="flex-grow-1 flex-shrink-0">
                                <a class="d-block btn bg--primary" href="{{route('admin.users.login',$user->id)}}"
                                    target="_blank">
                                    <i class="las la-sign-in-alt"></i> @lang('Login as User')
                                </a>
                            </li>
                            <li class="flex-grow-1 flex-shrink-0">
                                <a class="d-block btn bg--primary"
                                    href="{{ route('admin.users.notification.log',$user->id) }}">
                                    <i class="las la-bell"></i> @lang('Notifiactions')
                                </a>
                            </li>
                            <li class="flex-grow-1 flex-shrink-0">
                                <a class="d-block btn bg--primary"
                                    href="{{route('admin.report.login.history')}}?search={{ $user->username }}">
                                    <i class="las la-list-alt"></i> @lang('Login History')
                                </a>
                            </li>
                            <li class="flex-grow-1 flex-shrink-0">
                                @if($user->status == 1)
                                <a class="d-block btn bg--primary" class="userStatus" data-bs-toggle="modal"
                                    data-bs-target="#userStatusModal" href="javascript:void(0)">
                                    <i class="las la-ban"></i> @lang('Ban User')
                                </a>
                                @else
                                <a class="userStatus bg--primary" data-bs-toggle="modal"
                                    data-bs-target="#userStatusModal" href="javascript:void(0)">
                                    <i class="las la-undo"></i> @lang('Unban User')
                                </a>
                                @endif
                            </li>
                            <li class="flex-grow-1 flex-shrink-0">
                                <a class="d-block btn bg--primary"
                                    href="{{route('admin.users.notification.single', $user->id)}}">
                                    <i class="las la-paper-plane"></i> @lang('Send Email')
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('Information of') {{$user->fullname}}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.users.update',[$user->id])}}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group  col-xl-3 col-md-6 col-12">
                                    <label>@lang('Email Verification') </label>
                                    <label class="switch m-0">
                                        <input type="checkbox" class="toggle-switch" name="ev" {{ $user->ev ?
                                        'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="form-group  col-xl-3 col-md-6 col-12">
                                    <label>@lang('Mobile Verification') </label>
                                    <label class="switch m-0">
                                        <input type="checkbox" class="toggle-switch" name="sv" {{ $user->sv ?
                                        'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="form-group  col-xl-3 col-md-6 col-12">
                                    <label>@lang('2FA Verification') </label>
                                    <label class="switch m-0">
                                        <input type="checkbox" class="toggle-switch" name="ts" {{ $user->ts ?
                                        'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                               
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('First Name')</label>
                                        <input class="form-control" type="text" name="firstname" required
                                            value="{{$user->firstname}}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">@lang('Last Name')</label>
                                        <input class="form-control" type="text" name="lastname" required
                                            value="{{$user->lastname}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Email') </label>
                                        <input class="form-control" type="email" name="email" value="{{$user->email}}"
                                            required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Mobile Number') </label>
                                        <div class="input-group ">
                                            <span class="input-group-text mobile-code"></span>
                                            <input type="number" name="mobile" value="{{ old('mobile') }}" id="mobile"
                                                class="form-control checkUser" required>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label>@lang('Address')</label>
                                        <input class="form-control" type="text" name="address"
                                            value="{{ @$user->address->address }}">
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group">
                                        <label>@lang('City')</label>
                                        <input class="form-control" type="text" name="city"
                                            value="{{ @$user->address->city }}">
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('State')</label>
                                        <input class="form-control" type="text" name="state"
                                            value="{{ @$user->address->state }}">
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Zip/Postal')</label>
                                        <input class="form-control" type="text" name="zip"
                                            value="{{ @$user->address->zip }}">
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Country')</label>
                                        <select name="country" class="form-control">
                                            @foreach($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}">{{
                                                __($country->country) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="form-group  text-end mb-0">
                                        <button type="submit" class="btn btn--primary btn-global">@lang('Save')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection


@push('script')
<script>
    (function ($) {
        "use strict"
        let mobileElement = $('.mobile-code');
        $('select[name=country]').change(function () {
            mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);
        });

        $('select[name=country]').val('{{@$user->country_code}}');
        let dialCode = $('select[name=country] :selected').data('mobile_code');
        let mobileNumber = `{{ $user->mobile }}`;
        mobileNumber = mobileNumber.replace(dialCode, '');
        $('input[name=mobile]').val(mobileNumber);
        mobileElement.text(`+${dialCode}`);

    })(jQuery);
</script>
@endpush