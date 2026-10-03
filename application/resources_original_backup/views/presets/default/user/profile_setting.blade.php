@extends($activeTemplate.'layouts.master')
@section('content')
<div class="account-form">
    <div class="row gy-4">
        <div class="col-lg-4 border-end">
    
            <div class="dashboard_profile_wrap">
                <div class="profile_photo mb-2">
                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}"
                        alt="agent">
                    <div class="photo_upload">
                        <form action="{{ route('user.profile.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <label for="image"><i class="fas fa-image"></i></label>
                            <input id="image" type="file" name="image" class="upload_file"
                                onchange="this.form.submit()">
                        </form>
                    </div>
                </div>
            </div>
            <div class="contact-info">
                <h6 class="dash-title">@lang('Personal Information')</h6>
                <div class="info-wrap">
                    <div class="info">
                        <i class="fas fa-envelope"></i>
                        <p>@lang('Email Address')</p>
                    </div>
                    <p>{{ __($user->email) }}</p>
                </div>
            </div>
            <div class="contact-info">
                <div class="info-wrap">
                    <div class="info">
                        <i class="fas fa-phone"></i>
                        <p>@lang('Mobile Number')</p>
                    </div>
                    <p>{{ __(@$user->mobile) }}</p>
                </div>
            </div>
            <div class="contact-info">
                <div class="info-wrap">
                    <div class="info">
                        <i class="fas fa-map-marker"></i>
                        <p>@lang('Address')</p>
                    </div>
                    <p>{{ __(@$user->address->address) }},{{ __(@$user->address->state) }},
                        {{ __(@$user->address->zip) }} </p>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="profile-update">
                <h6 class="dash-title">@lang('Update profile')</h6>
                <div class="user-profile">
                    <form class="register" action="" method="post" enctype="multipart/form-data">
                        @csrf
    
                        <div class="row gy-4">
                            <div class="col-sm-6">
                                <div class="form-grp">
                                    <label class="label">@lang('First Name')</label>
                                    <input type="text" class="form-control form--control" name="firstname"
                                            value="{{$user->firstname}}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-grp">
                                    <label class="label">@lang('Last Name')</label>
                                    <input type="text" class="form-control form--control" name="lastname"
                                        value="{{$user->lastname}}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-grp">
                                    <label class="label">@lang('E-mail Address')</label>
                                    <input class="form-control form--control" value="{{$user->email}}" readonly>
                                </div>
                            </div>
                            
                            <div class="form-grp col-sm-6">
                                <label class="label">@lang('Mobile Number')</label>
                                <input class="form-control form--control" value="{{$user->mobile}}" readonly>
                            </div>
                            <div class="form-grp col-sm-6">
                                <label class="label">@lang('Address')</label>
                                <input type="text" class="form-control form--control" name="address"
                                    value="{{@$user->address->address}}">
                            </div>
                            <div class="form-grp col-sm-6">
                                <label class="label">@lang('State')</label>
                                <input type="text" class="form-control form--control" name="state"
                                    value="{{@$user->address->state}}">
                            </div>
    
                            <div class="form-grp col-sm-4">
                                <label class="label">@lang('Zip Code')</label>
                                <input type="text" class="form-control form--control" name="zip"
                                    value="{{@$user->address->zip}}">
                            </div>
    
                            <div class="form-grp col-sm-4">
                                <label class="label">@lang('City')</label>
                                <input type="text" class="form-control form--control" name="city"
                                    value="{{@$user->address->city}}">
                            </div>
    
                            <div class="form-grp col-sm-4">
                                <label class="label">@lang('Country')</label>
                                <input class="form-control form--control" value="{{@$user->address->country}}" disabled>
                            </div>
                            <div class="col-12 text-end">
                                <div class="form-grp">
                                    <button type="submit" class="btn btn--base">@lang('Update')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection