@php
    $cookie = App\Models\Frontend::where('data_keys','cookie.data')->first();
@endphp
@if(($cookie->data_values->status == 1) && !\Cookie::get('gdpr_cookie'))
    <!-- cookies dark version start -->
    <div class="d-flex justify-content-center">
      <div class="cookies-card text-center hide">
          <div class="cookies-card__content">{{ $cookie->data_values->short_desc }} <a class="text--base" href="{{ route('cookie.policy') }}" target="_blank">@lang('learn more')</a>
              <a href="javascript:void(0)" class="ms-4 btn btn--base policy">@lang('Allow')</a>
          </div>
      </div>
  </div>
  <!-- cookies dark version end -->
  @endif