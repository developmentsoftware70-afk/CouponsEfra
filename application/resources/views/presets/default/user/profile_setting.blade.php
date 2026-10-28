@extends($activeTemplate.'layouts.master')
@section('content')

<style>
    /* Profile Settings Premium UI */
    .profile-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 24px;
    }

    /* Profile Header Banner */
    .profile-header-banner {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        padding: 28px 24px 20px;
        color: #ffffff;
        position: relative;
    }
    .profile-header-content {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .profile-avatar-wrap {
        position: relative;
        width: 88px;
        height: 88px;
        flex-shrink: 0;
    }
    .profile-avatar-circle {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        background: #ffffff;
    }
    .profile-avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .profile-avatar-edit {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 28px;
        height: 28px;
        background: #ffffff;
        color: #0d6efd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
        transition: transform 0.2s ease, background-color 0.2s ease;
        border: 1.5px solid #0d6efd;
    }
    .profile-avatar-edit:hover {
        transform: scale(1.1);
        background: #f1f5f9;
    }
    .profile-header-info h4 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .profile-header-info p {
        color: rgba(255, 255, 255, 0.85);
        font-size: 13px;
        margin-bottom: 6px;
    }
    .profile-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(4px);
        color: #ffffff;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Form Body */
    .profile-form-body {
        padding: 28px 24px;
    }
    .profile-section-title {
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #0f172a;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
    }
    .profile-section-title i {
        font-size: 18px;
        color: #0d6efd;
    }

    /* Form Controls */
    .form-group-custom {
        margin-bottom: 18px;
    }
    .form-group-custom label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }
    .form-control-custom {
        width: 100%;
        height: 44px;
        padding: 10px 14px;
        font-size: 14px;
        color: #1e293b;
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control-custom:focus {
        outline: none;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
    }
    .form-control-custom[readonly],
    .form-control-custom:disabled {
        background-color: #f8fafc !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
        cursor: not-allowed;
    }

    /* Submit Button */
    .btn-update-profile {
        background: #0d6efd;
        color: #ffffff;
        font-weight: 600;
        font-size: 14px;
        padding: 11px 26px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.25);
    }
    .btn-update-profile:hover {
        background: #0b5ed7;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.35);
    }

    @media (max-width: 576px) {
        .profile-header-banner {
            padding: 20px 16px;
        }
        .profile-form-body {
            padding: 20px 16px;
        }
        .profile-header-content {
            gap: 14px;
        }
        .profile-avatar-wrap,
        .profile-avatar-circle {
            width: 72px;
            height: 72px;
        }
        .btn-update-profile {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10 col-12">

            <div class="profile-card">
                {{-- Form starts here so avatar image input is included --}}
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Profile Header Banner --}}
                    <div class="profile-header-banner">
                        <div class="profile-header-content">
                            <div class="profile-avatar-wrap">
                                <div class="profile-avatar-circle">
                                    <img id="profileImagePreview"
                                        src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}"
                                        alt="{{ $user->username }}">
                                </div>
                                <label for="profileImageInput" class="profile-avatar-edit" title="@lang('Change Photo')">
                                    <i class="las la-camera"></i>
                                </label>
                                <input type="file" id="profileImageInput" name="image" accept="image/png, image/jpeg, image/jpg" class="d-none">
                            </div>
                            <div class="profile-header-info">
                                <h4>{{ $user->fullname ?? ($user->firstname . ' ' . $user->lastname) }}</h4>
                                <p><i class="las la-envelope me-1"></i> {{ $user->email }}</p>
                                <span class="profile-badge">
                                    <i class="las la-check-circle"></i> @lang('Active Member')
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Form Body --}}
                    <div class="profile-form-body">

                        {{-- Section 1: Personal Details --}}
                        <div class="profile-section-title">
                            <i class="las la-user-circle"></i> @lang('Personal Details')
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group-custom">
                                    <label for="firstname">@lang('First Name') <span class="text-danger">*</span></label>
                                    <input type="text" id="firstname" class="form-control-custom" name="firstname"
                                        value="{{ $user->firstname }}" required placeholder="@lang('First Name')">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group-custom">
                                    <label for="lastname">@lang('Last Name') <span class="text-danger">*</span></label>
                                    <input type="text" id="lastname" class="form-control-custom" name="lastname"
                                        value="{{ $user->lastname }}" required placeholder="@lang('Last Name')">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group-custom">
                                    <label for="email">
                                        @lang('E-mail Address') 
                                        <span class="text-muted fw-normal" style="font-size: 11px;">(@lang('Read only'))</span>
                                    </label>
                                    <input type="email" id="email" class="form-control-custom" value="{{ $user->email }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group-custom">
                                    <label for="mobile">
                                        @lang('Mobile Number')
                                        <span class="text-muted fw-normal" style="font-size: 11px;">(@lang('Read only'))</span>
                                    </label>
                                    <input type="text" id="mobile" class="form-control-custom" value="{{ $user->mobile }}" readonly placeholder="@lang('Not provided')">
                                </div>
                            </div>
                        </div>

                        {{-- Section 2: Address & Location --}}
                        <div class="profile-section-title mt-4">
                            <i class="las la-map-marked-alt"></i> @lang('Address & Location')
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group-custom">
                                    <label for="address">@lang('Street Address')</label>
                                    <input type="text" id="address" class="form-control-custom" name="address"
                                        value="{{ @$user->address->address }}" placeholder="@lang('Enter your street address')">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group-custom">
                                    <label for="city">@lang('City')</label>
                                    <input type="text" id="city" class="form-control-custom" name="city"
                                        value="{{ @$user->address->city }}" placeholder="@lang('City')">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group-custom">
                                    <label for="state">@lang('State')</label>
                                    <input type="text" id="state" class="form-control-custom" name="state"
                                        value="{{ @$user->address->state }}" placeholder="@lang('State')">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group-custom">
                                    <label for="zip">@lang('Zip / Postal Code')</label>
                                    <input type="text" id="zip" class="form-control-custom" name="zip"
                                        value="{{ @$user->address->zip }}" placeholder="@lang('Zip Code')">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group-custom">
                                    <label for="country">@lang('Country')</label>
                                    <input type="text" id="country" class="form-control-custom"
                                        value="{{ @$user->address->country }}" disabled placeholder="@lang('Country')">
                                </div>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <div class="pt-3 mt-3 border-top d-flex justify-content-end">
                            <button type="submit" class="btn-update-profile">
                                <i class="las la-save" style="font-size: 18px;"></i>
                                <span>@lang('Save Changes')</span>
                            </button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@push('script')
<script>
    (function ($) {
        "use strict";
        // Live preview of uploaded avatar
        $('#profileImageInput').on('change', function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#profileImagePreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    })(jQuery);
</script>
@endpush

@endsection