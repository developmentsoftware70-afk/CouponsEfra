<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ $general->siteName(__($pageTitle)) }}</title>
    @include('includes.seo')
    @stack('seo')
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/common/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/common/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/common/css/line-awesome.min.css')}}">

    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/odometer.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/glightbox.min.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/slick.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/main.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/custom.css')}}">
    @stack('style-lib')
    @stack('style')

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/color.php') }}?color={{ $general->base_color }}&secondColor={{ $general->secondary_color }}">
    
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2807125110167312"
     crossorigin="anonymous"></script>
</head>
<body>
    <div class="sidebar-overlay"></div>
    <div id="loading">
        <div id="loading-center">
            <div id="loading-center-absolute">
                <span class="loader"></span>
            </div>
        </div>
    </div>
    @php
     $referByHomePage = session()->get('reference');
    @endphp

    @if(($referByHomePage && $referByHomePage == 2) || (!$referByHomePage && gs()->homepage == 2))
        @if(($referByHomePage && $referByHomePage == 2) || (!$referByHomePage && request()->is('/')))
            @include($activeTemplate.'components.home_two_header')
        @else 
            @include($activeTemplate.'components.header')
        @endif
    @else
        @include($activeTemplate.'components.header')
    @endif

    @if(request()->route()->uri != '/' && !request()->routeIs('coupon.details') && !request()->routeIs('deal.details'))
        @include($activeTemplate.'components.breadcrumb')
    @endif

    @yield('content')

    @include($activeTemplate.'components.footer')
    @include($activeTemplate.'components.cookie')
    <!-- Deal Modal (keep once) -->
    <div class="modal fade" id="dealModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="btn-wrap">
                    <button data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body justify-content-center align-items-center p-1">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <!-- Updated anchor with unique class -->
                                <a href="#" class="dealAnchor text-center" target="_blank">
                                    <div class="logo-thumb">
                                        <img class="dealImage img-fluid w-100" src="{{ asset('assets/images/general/logo.png') }}" alt="modal-image">
                                    </div>
                                </a>

                                <!-- Copy Link and Deal Button -->
                                <div class="d-flex gap-2 mt-3">
                                    <a href="#" class="btn btn--base dealAnchor copy-link-btn w-100">@lang('Copy Link')</a>
                                    <input type="text" class="dealLink visually-hidden" name="link">
                                    <a href="#" class="btn btn--base dealBtn w-100" target="_blank"><span class="buttonLabel"></span></a>
                                </div>

                                <div class="footer-item social-wrapper mt-4">
                                    <p class="mb-3">@lang('Share this deal now.')</p>
                                    <ul class="social-list">
                                        <li class="social-list__item">
                                            <a href="#" class="social-list__link share-link" target="_blank" data-social="facebook"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li class="social-list__item">
                                            <a href="#" class="social-list__link share-link" target="_blank" data-social="twitter"><i class="fab fa-twitter"></i></a>
                                        </li>
                                        <li class="social-list__item">
                                            <a href="#" class="social-list__link share-link" target="_blank" data-social="linkedin"><i class="fab fa-linkedin-in"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-7 mb-3 scroll_section_popup">
                                <div class="p-2">
                                    {{-- <h5 class="dealTitle mt-3"></h5> --}}
                                    <div class="dealDescription mt-2"></div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Required Modal -->
    <div class="modal fade" id="loginRequiredModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 380px;">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; top: 16px; right: 16px; z-index: 10; font-size: 14px; opacity: 0.5;"></button>
                
                <div class="modal-body" style="padding: 24px 28px;">
                    <!-- Logo -->
                    <div class="text-center mb-4 mt-2">
                        <h3 style="font-weight: 800; font-size: 22px; color: #111827; margin: 0; font-family: 'Inter', sans-serif;">
                            Coupons<span style="color: #00c283;">Efra</span>
                        </h3>
                    </div>

                    <!-- Tabs -->
                    <div class="d-flex mb-4" style="border-bottom: 1px solid #fecaca;">
                        <a href="javascript:void(0)" class="flex-fill text-center pb-2" style="font-size: 14px; font-weight: 600; color: #00c283; border-bottom: 2px solid #00c283; text-decoration: none; margin-bottom: -1px;">Login</a>
                        <a href="{{ route('user.register') }}" class="flex-fill text-center pb-2" style="font-size: 14px; font-weight: 600; color: #94a3b8; text-decoration: none; border-bottom: 2px solid transparent; margin-bottom: -1px;">Register</a>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('user.login') }}">
                        @csrf
                        
                        <div class="mb-3 text-start">
                            <label style="font-size: 13px; color: #4b5563; margin-bottom: 6px; font-weight: 500;">Email Address</label>
                            <input type="text" name="username" class="form-control" placeholder="Admin" style="background-color: #f0f7ff; border: 1px solid #b6d4fe; border-radius: 6px; padding: 10px 14px; font-size: 14px; color: #374151; box-shadow: none;" required>
                        </div>

                        <div class="mb-4 text-start">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label style="font-size: 13px; color: #4b5563; margin: 0; font-weight: 500;">Password</label>
                                <a href="{{ route('user.password.request') }}" style="font-size: 12px; color: #00c283; text-decoration: none; font-weight: 600;">Forgot Password?</a>
                            </div>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" style="background-color: #f0f7ff; border: 1px solid #b6d4fe; border-radius: 6px; padding: 10px 14px; font-size: 16px; color: #374151; letter-spacing: 2px; box-shadow: none;" required>
                        </div>

                        <button type="submit" class="btn w-100 modal-auth-btn" style="background-color: #00c283; color: #fff; font-weight: 600; padding: 10px; border-radius: 6px; font-size: 15px; border: 1px solid #065f46; transition: background-color 0.2s;">
                            Sign In
                        </button>
                    </form>

                    <div class="position-relative text-center my-4">
                        <hr style="border-color: #e5e7eb; margin: 0;">
                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 0 12px; color: #9ca3af; font-size: 11px; font-weight: 600; letter-spacing: 0.5px;">
                            OR CONTINUE WITH
                        </span>
                    </div>

                    <a href="{{ route('google.redirect') }}" class="btn w-100 d-flex justify-content-center align-items-center modal-google-btn" style="border: 1px solid #111827; background: #fff; color: #374151; padding: 8px; border-radius: 6px; font-weight: 500; font-size: 14px; transition: background 0.2s;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google" style="width: 18px; margin-right: 10px;">
                        Google
                    </a>
                    
                </div>
                
                <style>
                    .modal-auth-btn:hover { background-color: #059669 !important; }
                    .modal-google-btn:hover { background-color: #f9fafb !important; }
                    #loginRequiredModal .form-control:focus { border-color: #00c283; box-shadow: 0 0 0 0.2rem rgba(0, 194, 131, 0.25); background-color: #fff; }
                </style>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{asset('assets/common/js/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('assets/common/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{asset($activeTemplateTrue.'js/odometer.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'js/jquery.appear.min.js')}}"></script>

    <script src="{{asset($activeTemplateTrue.'js/bootstrap.min.js')}}"></script>

    <script src="{{asset($activeTemplateTrue.'js/slick.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'js/wow.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'js/main.js')}}"></script>

    @stack('script-lib')
    @stack('script')
    @include('includes.plugins')
    @include('includes.notify')
    <script>
        (function($) {
            "use strict";
            // Direct Coupon Detail Navigation for all users (Guests & Logged In)
            $(document).off('click', '.getCoupon').on('click', '.getCoupon', function(e) {
                var href = $(this).attr('href');
                if (href && href !== '#' && href.indexOf('javascript:') === -1) {
                    // Real link: let natural browser navigation take place
                    return true;
                }

                e.preventDefault();
                var slug = $(this).data('slug');
                var couponId = $(this).data('id');
                var target = slug || couponId;
                if (target) {
                    window.location.href = "{{ url('/coupon') }}/" + target;
                }
            });

            // Universal Card Click: Make entire card clickable across all coupon & deal cards
            $(document).on('click', '.vertical-coupon-card, .coupon-card, .deal-card', function(e) {
                // If the click was already on a link, button, or wishlist, let it handle directly
                if ($(e.target).closest('a, button').length) {
                    return;
                }
                var targetBtn = $(this).find('.vertical-grab-btn, .getCoupon, .getDeal, a.btn, a').first();
                if (targetBtn.length) {
                    targetBtn[0].click();
                }
            });

            // Auto reload fresh page if restored from browser back/forward cache
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    window.location.reload();
                }
            });

            @auth
                try {
                    localStorage.setItem('couponsefra_auth_sync', '{{ auth()->user()->id }}_' + Date.now());
                } catch(e) {}
            @else
                try {
                    var initialAuth = localStorage.getItem('couponsefra_auth_sync');
                    // When user logs in in another tab (e.g. from Grab Deal in new tab), auto-reload this tab so header shows logged in!
                    window.addEventListener('storage', function(e) {
                        if (e.key === 'couponsefra_auth_sync' && e.newValue) {
                            window.location.reload();
                        }
                    });
                    // On tab focus, check if login occurred in the other tab
                    window.addEventListener('focus', function() {
                        var currentAuth = localStorage.getItem('couponsefra_auth_sync');
                        if (currentAuth && currentAuth !== initialAuth) {
                            window.location.reload();
                        }
                    });
                } catch(e) {}
            @endauth
            // add to wishlist
            $(document).on('click', '.addToWishList', function() {
                'use strict';
                var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
                if (isLoggedIn) {
                    var couponId = $(this).data('id');
                    var button = $(this);
                    $.ajax({
                        url: '{{ route('user.wishlist.add') }}',
                        type: 'get',
                        data: {
                            couponId: couponId,
                        },
                        success: function(response) {
                            if (response.hasOwnProperty('message')) {
                                Toast.fire({
                                    icon: 'success',
                                    title: response.message
                                });
                                var heartIcon = button.find('i');
                                heartIcon.removeClass('far fa-heart').addClass('fas fa-heart');
                            } else {
                                Toast.fire({
                                    icon: 'warning',
                                    title: response.error
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            var errorMessage =
                            'Error occurred while adding the Coupon to wishlist.';
                            Toast.fire({
                                icon: 'error',
                                title: errorMessage
                            });
                        }
                    });
                } else {
                    var errorMessage = 'Please log in to add items to your wishlist.';
                    Toast.fire({
                        icon: 'warning',
                        title: errorMessage
                    });
                }
            });
            // end wishlist

        })(jQuery);
    </script>
    <script>
        (function($) {
            "use strict";
            // Event delegation for dynamically added content
            $(document).on('click', '.getDeal', function(e) {
                e.preventDefault();
                @auth
                    const modal = $('#dealModal');

                    const title = $(this).data('title');
                    const description = $(this).data('description');
                    let link = $(this).data('link');
                    const button_label = $(this).data('button_label');
                    const dealId = $(this).data('id');

                    var cleanUsername = '{{ str_replace(["-", " "], "", auth()->user()->username) }}';
                    var userId = '{{ auth()->user()->id }}';
                    var trackingId = cleanUsername + '_' + userId;
                    
                    if (link && link.indexOf('source=') === -1) {
                        if (link.indexOf('?') !== -1) {
                            link = link + '&source=' + trackingId;
                        } else {
                            link = link + '?source=' + trackingId;
                        }
                    }

                    modal.find('.buttonLabel').text(button_label ?? '{{ __("Grab Deal") }}');
                    modal.find('.dealDescription').html(description);
                    modal.find('.dealAnchor').attr('href', link);
                    modal.find('.dealBtn').attr('href', link);

                    modal.find('input[name=link]').val(link);
                    modal.find('.dealLink').val(link);

                    // Update share links
                    $('.share-link[data-social="facebook"]').attr('href', 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(link));
                    $('.share-link[data-social="twitter"]').attr('href', 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(link));
                    $('.share-link[data-social="linkedin"]').attr('href', 'https://www.linkedin.com/shareArticle?url=' + encodeURIComponent(link));

                    getDealInfo(dealId);
                    modal.modal('show');
                @else
                    $('#loginRequiredModal').modal('show');
                @endauth
            });
            // Fix: Use delegated binding for copy link button
            $(document).on('click', '.copy-link-btn', function(e) {
                e.preventDefault();
                const link = $('.dealLink').val();

                if (!link) return alert('Link not found.');

                navigator.clipboard.writeText(link).then(function () {
                    const notification = $('.coupon-code-notification');
                    notification.length
                        ? notification.text('Link Copied').fadeIn().delay(1000).fadeOut(100)
                        : alert('Link Copied: ' + link);
                }).catch(function (err) {
                    alert('Failed to copy: ' + err);
                });
            });
            function getDealInfo(dealId) {
                $.ajax({
                    url: '{{ route('get.modal.dealInfo') }}',
                    type: 'get',
                    data: { dealId: dealId },
                    success: function(response) {
                        if (response.dealImage) {
                            $('.dealImage').attr('src', response.dealImage);
                        } else if (response.dealCatImage) {
                            $('.dealImage').attr('src', response.dealCatImage);
                        } else {
                            console.error('Deal image not found');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching deal info:', error);
                    }
                });
            }
        })(jQuery);
    </script>
    <script>
        (function ($) {
            "use strict";
            $(".langSel").on("change", function() {
                window.location.href = "{{route('home')}}/change/"+$(this).val() ;
            });

            var inputElements = $('input,select');
            $.each(inputElements, function (index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for',element.attr('name'));
                element.attr('id',element.attr('name'))
            });

            $('.policy').on('click',function(){
                $.get('{{route('cookie.accept')}}', function(response){
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function(){
                $('.cookies-card').removeClass('hide')
            },2000);

            var inputElements = $('[type=text],select,textarea');
            $.each(inputElements, function (index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for',element.attr('name'));
                element.attr('id',element.attr('name'))
            });

            $.each($('input, select, textarea'), function (i, element) {

                if (element.hasAttribute('required')) {
                    $(element).closest('.form-group').find('label').addClass('required');
                }

            });

        })(jQuery);
    </script>

    @guest
    <script>
        (function($) {
            "use strict";
            // For direct affiliate redirects where cashback activation requires login
            $(document).on('click', '.slider-banner-link, .single-slide-link', function(e) {
                e.preventDefault();
                $('#loginRequiredModal').modal('show');
                return false;
            });

            // Cross-tab auto-detection when user logs in on another tab
            var isReloading = false;
            function reloadIfLoggedIn() {
                if (isReloading) return;

                var hasSync = false;
                try {
                    hasSync = !!localStorage.getItem('couponsefra_auth_sync');
                } catch(e) {}

                var hasCookie = document.cookie.indexOf('couponsefra_logged_in=1') !== -1;

                if (hasSync || hasCookie) {
                    isReloading = true;
                    try { localStorage.removeItem('couponsefra_auth_sync'); } catch(e) {}
                    window.location.reload();
                    return;
                }

                // Verify with server endpoint when tab is focused
                fetch('{{ route("check.auth") }}', { credentials: 'same-origin' })
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        if (data && data.logged_in) {
                            isReloading = true;
                            try { localStorage.removeItem('couponsefra_auth_sync'); } catch(e) {}
                            window.location.reload();
                        }
                    })
                    .catch(function() {});
            }

            // 1. BroadcastChannel API for instantaneous cross-tab sync
            if (typeof BroadcastChannel !== 'undefined') {
                try {
                    var authChannel = new BroadcastChannel('couponsefra_auth_channel');
                    authChannel.onmessage = function(ev) {
                        if (ev && ev.data && ev.data.type === 'LOGIN') {
                            reloadIfLoggedIn();
                        }
                    };
                } catch(e) {}
            }

            // 2. Storage event (fires instantly in other tabs on localStorage.setItem)
            window.addEventListener('storage', function(e) {
                if (e.key === 'couponsefra_auth_sync' && e.newValue) {
                    reloadIfLoggedIn();
                }
            });

            // 3. Tab visibilitychange (fires when user switches back to this tab)
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'visible') {
                    reloadIfLoggedIn();
                }
            });

            // 4. Tab focus
            window.addEventListener('focus', function() {
                reloadIfLoggedIn();
            });

            // 5. Back-forward cache / pageshow
            window.addEventListener('pageshow', function(e) {
                if (e.persisted) {
                    reloadIfLoggedIn();
                }
            });
        })(jQuery);
    </script>
    @endguest

    @auth
    <script>
        (function($) {
            "use strict";

            // Signal to other open tabs that user is logged in
            try {
                localStorage.setItem('couponsefra_auth_sync', Date.now().toString());
                document.cookie = "couponsefra_logged_in=1; path=/; max-age=2592000";
                if (typeof BroadcastChannel !== 'undefined') {
                    var authBc = new BroadcastChannel('couponsefra_auth_channel');
                    authBc.postMessage({ type: 'LOGIN', timestamp: Date.now() });
                    authBc.close();
                }
            } catch(e) {}

            var currentTrackingId = '{{ str_replace(["-", " "], "", auth()->user()->username) }}_{{ auth()->user()->id }}';

            function addTrackingSource(url) {
                if (!url || typeof url !== 'string') return url;
                if (url.indexOf('javascript:') === 0 || url.indexOf('#') === 0) return url;

                try {
                    var parsed = new URL(url, window.location.origin);
                    if (parsed.hostname !== window.location.hostname && (parsed.protocol === 'http:' || parsed.protocol === 'https:')) {
                        if (parsed.searchParams.get('source') === currentTrackingId) {
                            return url;
                        }
                        parsed.searchParams.set('source', currentTrackingId);
                        return parsed.toString();
                    }
                } catch(e) {
                    if ((url.indexOf('http://') === 0 || url.indexOf('https://') === 0 || url.indexOf('//') === 0) &&
                        url.indexOf(window.location.hostname) === -1) {
                        if (/[?&]source=/.test(url)) {
                            return url.replace(/([?&]source=)[^&]*/, '$1' + currentTrackingId);
                        } else {
                            var sep = url.indexOf('?') !== -1 ? '&' : '?';
                            return url + sep + 'source=' + currentTrackingId;
                        }
                    }
                }
                return url;
            }

            // Universal interceptor for external clicks on dashboard and entire site
            $(document).on('click', 'a', function(e) {
                var href = $(this).attr('href');
                if (href && (href.indexOf('http://') === 0 || href.indexOf('https://') === 0 || href.indexOf('//') === 0)) {
                    var newHref = addTrackingSource(href);
                    if (newHref !== href) {
                        $(this).attr('href', newHref);
                    }
                }
            });
        })(jQuery);
    </script>
    @endauth

</body>
</html>


