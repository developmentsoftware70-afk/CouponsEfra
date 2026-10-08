@php
    $latestCoupon = getContent('latest_coupon.content', true);
    $latestCouponsData = App\Models\Coupon::with(['category', 'store', 'wishlists'])
        ->where('status', 1)
        ->latest()
        ->limit(8)
        ->get();
@endphp

<section class="exclusive-section py-20 bg-light">
    <div class="container-fluid container-custom">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            {{ __($latestCoupon->data_values->heading) }}
                        </h2>
                        {{-- 
                        <a href="{{ route('latest.coupons') }}" class="view-more-link">@lang('View More')</a> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 ck-slider-wrap">
                <div class="ck-latest-slider">
            @php
                $ckTheme = [
                    ['bg' => 'linear-gradient(135deg, #ff9900, #ff5500)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
                    ['bg' => 'linear-gradient(135deg, #2874f0, #0053c8)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
                    ['bg' => 'linear-gradient(135deg, #c74c10, #802802)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
                    ['bg' => 'linear-gradient(135deg, #00c6ff, #0072ff)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
                    ['bg' => 'linear-gradient(135deg, #f093fb, #f5576c)', 'btn' => '#0053c8', 'badge' => '#ffd700']
                ];
            @endphp
            @foreach ($latestCouponsData as $index => $item)
            <div class="slider-item">
                <div class="vertical-coupon-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                    <div class="vertical-card-img">
                        @if($item->image)
                            <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" alt="@lang('Coupon Image')">
                        @elseif($item->store && $item->store->image)
                            <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" alt="">
                        @endif
                    </div>
                    
                    <a href="javascript:void(0)" class="vertical-grab-btn getCoupon" 
                        data-id="{{$item->id}}" data-title="{{$item->title}}" 
                        data-code="{{$item->code}}" data-description="{{$item->description}}" 
                        data-link="{{$item->link}}">
                        Grab Deal
                    </a>
                </div>
            </div>
            @endforeach
                </div>
            </div>
        </div>
        </div>
        <div class="row pt-2">
            <div class="col-lg-12 text-end">
                <a href="{{ route('latest.coupons') }}" class="view-more-link">@lang('View More')</a>
            </div>
        </div>
    </div>
</section>
<!-- Modal (keep once) -->
{{-- 
<div class="modal fade" id="couponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="btn-wrap">
                <button data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body justify-content-center align-items-center p-1">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="#" class="couponName" target="_blank">
                                <div class="logo-thumb">
                                    <img class="couponImage img-fluid" src="{{ asset('assets/images/general/logo.png') }}" alt="">
                                </div>
                            </a>
                            <div class="d-flex gap-2 mt-3">
                                <div class="copy-code-input">
                                    <input class="form--control coponCodeCopyFeature" name="code" readonly>
                                    <a href="" class="btn btn--base copy-btn copytext" id="copyBoard-feature" target="_blank"><i class="fas fa-copy"></i></a>
                                </div>
                                <a href="" class="btn btn--base couponName" id="copyLink" target="_blank">@lang('Copy Link')</a>
                                <input type="text" class="couponLinkCopyFeature visually-hidden" name="link">
                                <a href="#" class="btn btn--base couponName" target="_blank">@lang('Grab Deal')</a>
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
                        <div class="col-md-6 mb-3 scroll_section_popup">
                            <div class="p-2">
                                <p class="title"></p> 
                                <div class="description mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        (function($) {
            "use strict";
            // .getCoupon.on('click', function() {
                var modal = $('#couponModal');

                modal.find('.title').text($(this).data('title'));
                modal.find('.description').text($(this).data('description'));
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

            // Slick Slider initialization for ck-latest-slider
            if ($('.ck-latest-slider').length > 0) {
                $('.ck-latest-slider').slick({
                    dots: false,
                    infinite: true,
                    speed: 300,
                    slidesToShow: 3.2,
                    slidesToScroll: 1,
                    arrows: true,
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 2.2,
                            }
                        },
                        {
                            breakpoint: 600,
                            settings: {
                                slidesToShow: 1.2,
                            }
                        }
                    ]
                });
        })(jQuery);
    </script>
@endpush
 --}}

@push('script')
<script>
    (function($) {
        "use strict";
        // Slick Slider initialization for ck-latest-slider
        if ($('.ck-latest-slider').length > 0) {
            $('.ck-latest-slider').slick({
                dots: false,
                infinite: false,
                speed: 300,
                slidesToShow: 3.2,
                slidesToScroll: 1,
                arrows: true,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2.2,
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1.2,
                        }
                    }
                ]
            });
        }
    })(jQuery);
</script>
@endpush
