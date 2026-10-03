@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $contact = getContent('contact_us.content', true);
    @endphp
    <!-- ==================== Contact Form Start Here ==================== -->
    <section class="contact-bottom section-bg py-20">
        <div class="container-fluid container-custom">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-content">
                        <div class="title-wrap">
                            <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                                {{ __($contact->data_values->title) }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gy-3 justify-content-center">
                <div class="col-lg-5 wow animate__animated animate__fadeInUp">
                    <div class="col-lg-12">
                        <div class="contact-info">
                            <div class="contact-info__addres-wrap">
                                <div class="single_wrapper">
                                    <h4>@lang('Call Us')</h4>
                                    <div class="single-info">
                                        <div class="cont-icon">
                                            <i class="far fa-envelope"></i>
                                        </div>
                                        <div class="cont-text">
                                            <h6><a
                                                    href="tel:{{ __($contact->data_values->contact_number) }}">{{ __($contact->data_values->contact_number) }}</a>
                                            </h6>
                                            <h6><a
                                                    href="tel:{{ __($contact->data_values->contact_number_two) }}">{{ __($contact->data_values->contact_number_two) }}</a>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 wow animate__animated animate__fadeInUp">
                        <div class="contact-info">
                            <div class="contact-info__addres-wrap">
                                <div class="single_wrapper">
                                    <h4>@lang('Office')</h4>
                                    <div class="single-info">
                                        <div class="cont-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="cont-text">
                                            <h6><a
                                                    href="javascript:void(0)">{{ __($contact->data_values->contact_details) }}</a>
                                            </h6>
                                            <h6><a
                                                    href="javascript:void(0)">{{ __($contact->data_values->contact_details_two) }}</a>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="contact-info  border-none">
                            <div class="contact-info__addres-wrap">
                                <div class="single_wrapper">
                                    <h4>@lang('Email')</h4>
                                    <div class="single-info">
                                        <div class="cont-icon">
                                            <i class="far fa-envelope"></i>
                                        </div>
                                        <div class="cont-text">
                                            <h6><a
                                                    href="mailto:{{ __($contact->data_values->email_address) }}">{{ __($contact->data_values->email_address) }}</a>
                                            </h6>
                                            <h6><a
                                                    href="mailto:{{ __($contact->data_values->email_address_two) }}">{{ __($contact->data_values->email_address_two) }}</a>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contactus-form wow animate__animated animate__fadeInUp">
                        <form method="post" action="" class="verify-gcaptcha">
                            @csrf
                            <div class="row gy-md-4 gy-3">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input name="name" type="text" class="form--control" placeholder=""
                                            value="{{ old('name') }}" required>
                                        <label class="form--label">@lang('Name')</label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input name="email" type="email" class="form--control" placeholder=""
                                            value="@if (auth()->user()) {{ auth()->user()->email }}@else{{ old('email') }} @endif"
                                            @if (auth()->user()) readonly @endif required>
                                        <label class="form--label">@lang('Email')</label>

                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input name="subject" type="text" class="form--control" placeholder=""
                                            value="{{ old('subject') }}" required>
                                        <label class="form--label">@lang('Subject')</label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <textarea class="form--control" placeholder="" name="message">{{ old('message') }}</textarea>
                                        <label class="form--label">@lang('Message')</label>
                                    </div>

                                </div>
                                <x-captcha></x-captcha>
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn--base">
                                        @lang('Send Your Message')<i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==================== Contact Form End Here ==================== -->

    <!-- ==================== Map Start Here ==================== -->
    <div>
        <div class="container-fluid container-custom">
            <div class="row">
                <div class="col-12">
                    <div class="contact-map wow animate__animated animate__fadeInUp">
                        <iframe
                            src="https://maps.google.com/maps?q={{ $contact->data_values->latitude }},{{ $contact->data_values->longitude }}&hl=es;z=14&amp;output=embed"
                            width="600" height="450" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================== Map Start Here ==================== -->
@endsection
