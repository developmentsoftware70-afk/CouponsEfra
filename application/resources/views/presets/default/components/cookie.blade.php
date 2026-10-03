@php
    $cookie = App\Models\Frontend::where('data_keys','cookie.data')->first();
@endphp
@if($cookie->data_values->status == 1)
    <!-- cookies dark version start -->
    <style>
        .cookies-card {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 16px 24px;
            border-radius: 12px;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s ease;
        }
        .cookies-card.d-none {
            display: none !important;
        }
        .cookies-card__content {
            font-size: 14px;
            color: #475569;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .cookies-card__content a.text--base {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }
        .cookies-card__content .btn.policy {
            background: #0f172a;
            color: #ffffff;
            padding: 8px 20px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            border: none;
            transition: background 0.3s ease;
        }
        .cookies-card__content .btn.policy:hover {
            background: #334155;
        }
    </style>
    <div class="cookies-card text-center">
        <div class="cookies-card__content">
            <span>{{ $cookie->data_values->short_desc }} <a class="text--base" href="{{ route('cookie.policy') }}" target="_blank">@lang('learn more')</a></span>
            <a href="javascript:void(0)" class="btn btn--base policy">@lang('Allow')</a>
        </div>
    </div>
    <!-- cookies dark version end -->
@endif