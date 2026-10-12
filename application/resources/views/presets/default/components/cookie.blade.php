@php
    $cookie = App\Models\Frontend::where('data_keys','cookie.data')->first();
@endphp
@if(!\Cookie::get('gdpr_cookie') && !isset($_COOKIE['gdpr_declined']))
    <style>
        .cookies-card {
            position: fixed;
            bottom: 0; /* Ekdam footer me bottom par fix */
            left: 0;
            width: 100%;
            background-color: #ffffff;
            border-top: 1px solid #e5e7eb; /* Border upar ki taraf */
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.05); /* Shadow upar ki taraf */
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            font-family: Arial, Helvetica, sans-serif;
            color: #000000;
        }
        
        .cookies-card.d-none {
            display: none !important;
        }

        .cookie-content-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .cookie-text-section {
            display: flex;
            flex-direction: column;
            gap: 4px;
            text-align: left;
        }

        .cookie-title {
            font-weight: bold;
            font-size: 15px;
            margin: 0;
            color: #000000;
        }

        .cookie-desc {
            font-size: 13px;
            margin: 0;
            color: #333333;
        }

        .cookie-desc a {
            color: #333333;
            text-decoration: underline;
        }

        .cookie-action-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Accept Button - Orange color exactly like screenshot */
        .btn-cookie-accept {
            color: #f59e0b; 
            font-weight: bold;
            font-size: 14px;
            text-decoration: none;
            background: transparent;
            border: none;
            cursor: pointer;
        }
        
        .btn-cookie-accept:hover {
            text-decoration: underline;
        }

        /* Decline Button - White box with black border exactly like screenshot */
        .btn-cookie-decline {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #cccccc;
            border-radius: 4px;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-cookie-decline:hover {
            background-color: #f3f4f6;
        }

        @media (max-width: 768px) {
            .cookie-content-wrap {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            .cookie-action-section {
                width: 100%;
                justify-content: flex-end;
            }
        }
    </style>

    <div class="cookies-card">
        <div class="cookie-content-wrap">
            <div class="cookie-text-section">
                <!-- Heading wahi jo screenshot me thi -->
                <p class="cookie-title">We use cookies on this site to enhance your user experience</p>
                <!-- Niche ka text backend se aayega website ke baare me -->
                <p class="cookie-desc">
                    {{ $cookie->data_values->short_desc }} 
                    <a href="{{ route('cookie.policy') }}" target="_blank">@lang('More info')</a>
                </p>
            </div>
            <div class="cookie-action-section">
                <a href="javascript:void(0)" class="btn-cookie-accept policy">@lang('Accept')</a>
                <a href="javascript:void(0)" class="btn-cookie-decline decline-policy">@lang('Decline')</a>
            </div>
        </div>
    </div>
    
    <script>
        // Custom logic for Decline Button (Real functionality)
        document.addEventListener('DOMContentLoaded', function() {
            var declineBtn = document.querySelector('.decline-policy');
            if(declineBtn) {
                declineBtn.addEventListener('click', function() {
                    // Hide the banner immediately
                    document.querySelector('.cookies-card').classList.add('d-none');
                    // Set a "Session Cookie" so the banner doesn't pop up on every page click today,
                    // but it WILL come back when they close and reopen the browser next time!
                    document.cookie = "gdpr_declined=1; path=/";
                });
            }
        });
    </script>
@endif