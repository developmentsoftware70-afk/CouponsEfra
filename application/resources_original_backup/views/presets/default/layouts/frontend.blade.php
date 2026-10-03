<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ $general->siteName(__($pageTitle)) }}</title>
    @include('includes.seo')
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

    @if(request()->route()->uri != '/')
        @include($activeTemplate.'components.breadcrumb')
    @endif

    @yield('content')

    @include($activeTemplate.'components.footer')
    @include($activeTemplate.'components.cookie')
    <!-- Modal (keep once) -->
    <div class="modal fade" id="couponModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="btn-wrap">
                    <button data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body justify-content-center align-items-center p-1">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <a href="#" class="couponName text-center" target="_blank">
                                    <div class="logo-thumb">
                                        <img class="couponImage img-fluid w-100" src="{{ asset('assets/images/general/logo.png') }}" alt="">
                                    </div>
                                </a>
                                <div class="d-flex gap-2 mt-3">
                                    {{-- 
                                    <div class="copy-code-input">
                                        <input class="form--control coponCodeCopyFeature" name="code" readonly>
                                        <a href="" class="btn btn--base copy-btn copytext" id="copyBoard-feature" target="_blank"><i class="fas fa-copy"></i></a>
                                    </div>
                                    --}}
                                    
                                    <a href="" class="btn btn--base copy-btn copytext w-100" id="copyBoard-feature" target="_blank">@lang('Copy Code')</a>
                                    <input type="text" class="coponCodeCopyFeature visually-hidden" name="code">
                                    <a href="" class="btn btn--base couponName w-100" id="copyLink" target="_blank">@lang('Copy Link')</a>
                                    <input type="text" class="couponLinkCopyFeature visually-hidden" name="link">
                                    <a href="#" class="btn btn--base couponName w-100" target="_blank">@lang('Grab Deal')</a>
                                </div>
                                <div class="footer-item social-wrapper mt-4">
                                    <p class="mb-3">@lang('Share this coupon now.')</p>
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
                                    {{-- <p class="title"></p> --}}
                                    <div class="description mt-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal (keep once) -->
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
            $('.getCoupon').on('click', function() {
                var modal = $('#couponModal');

                modal.find('.title').text($(this).data('title'));
                modal.find('.description').html($(this).data('description'));
                modal.find('input[name=code]').val($(this).data('code'));
                modal.find('.couponName').attr('href', $(this).data('link'));
                modal.find('.storeName').attr('href', $(this).data('link'));
                modal.find('.copytext').attr('href', $(this).data('link'));

                modal.find('input[name=link]').val($(this).data('link'));
                modal.find('.couponLinkCopyFeature').val($(this).data('link'));

                var couponId = $(this).data('id');
                getCouponInfo(couponId)

                var shareLink = $(this).data('link');
                // Update the href attribute of the share links
                $('.share-link[data-social="facebook"]').attr('href',
                    'https://www.facebook.com/sharer/sharer.php?u=' + shareLink);
                $('.share-link[data-social="twitter"]').attr('href',
                    'https://twitter.com/intent/tweet?url=' + shareLink);
                $('.share-link[data-social="linkedin"]').attr('href',
                    'https://www.linkedin.com/shareArticle?url=' + shareLink);

                modal.modal('show');
            });

            $('#copyBoard-feature').on('click', function(e) {
                e.preventDefault();

                var copyText = document.querySelector(".coponCodeCopyFeature");

                if (copyText) {
                    copyText.select();
                    copyText.setSelectionRange(0, 99999); // For mobile compatibility
                    document.execCommand("copy");
                    copyText.blur();

                    // Show notification (if exists)
                    var notification = $('.coupon-code-notification');
                    if (notification.length > 0) {
                        notification.text('Copied Code').fadeIn().delay(1000).fadeOut(100);
                    } else {
                        // Fallback alert
                        alert('Copied: ' + copyText.value);
                    }

                    // Optional: add 'copied' class for styling
                    $(this).addClass('copied');

                    // Open the link in new tab after a short delay
                    var href = $(this).attr('href');
                    if (href && href !== '#') {
                        setTimeout(function() {
                            window.open(href, '_blank');
                        }, 2000);
                    }
                } else {
                    alert('Copy target not found.');
                }
            });
            $('#copyLink').on('click', function(e) {
                e.preventDefault();

                const link = $('.couponLinkCopyFeature').val();
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

            // get modal into product info
            function getCouponInfo(couponId) {
                $.ajax({
                    url: '{{ route('get.modal.info') }}',
                    type: 'get',
                    data: {
                        couponId: couponId,
                    },
                    success: function(response) {
                        if (response.couponImage) {
                            $('.couponImage').attr('src', response.couponImage);
                        } else if (response.storeImage) {
                            $('.couponImage').attr('src', response.storeImage);
                        } else {
                            console.error('store image could not found');
                        }

                    },
                    error: function(error) {
                        console.error('Error fetching product info:', error);
                    }
                });
            }
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
                const modal = $('#dealModal');

                const title = $(this).data('title');
                const description = $(this).data('description');
                const link = $(this).data('link');
                const button_label = $(this).data('button_label');
                const dealId = $(this).data('id');

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

</body>
</html>
