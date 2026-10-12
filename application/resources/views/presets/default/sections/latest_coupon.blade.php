@php
    $latestCoupon = getContent('latest_coupon.content', true);
    $latestCouponsData = App\Models\Coupon::with(['category', 'store', 'wishlists'])
        ->where('status', 1)
        ->latest()
        ->limit(12)
        ->get();
@endphp

<section class="flash-deals-section" id="flash-deals-section">
    {{-- Background Video --}}
    <video class="flash-deals-bg-video" autoplay loop muted playsinline poster="{{ asset('assets/presets/default/images/bg/flash-deals-bg.jpg') }}">
        <source src="{{ asset('assets/presets/default/images/bg/flash-deals-video.mp4') }}" type="video/mp4">
        <source src="{{ asset('WhatsApp Video 2026-10-12 at 16.40.33.mp4') }}" type="video/mp4">
    </video>
    <div class="flash-deals-bg-overlay"></div>
    <div class="container-fluid container-custom position-relative" style="z-index: 2;">
        {{-- CashKaro Style Header with Title & Live Countdown Timer --}}
        <div class="row">
            <div class="col-12 text-center">
                <div class="flash-deals-header">
                    <h2 class="flash-deals-main-title wow animate__animated animate__fadeInDown">
                        {{ __($latestCoupon->data_values->heading == 'Coupons' ? 'FLASH DEALS' : $latestCoupon->data_values->heading) }}
                    </h2>
                    <div class="flash-deals-timer-badge wow animate__animated animate__fadeInUp" data-wow-delay="0.1s">
                        <i class="far fa-clock"></i> Ends in <span id="flashDealCountdownTimer">02:45:39</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CashKaro Style Flash Deal Slider --}}
        <div class="row">
            <div class="col-12 flash-deals-slider-wrap">
                <div class="ck-latest-slider">
                    @foreach ($latestCouponsData as $index => $item)
                        <div class="slider-item">
                            <div class="flash-deal-card-wrapper position-relative">
                                {{-- Heart / Wishlist Button --}}
                                <button type="button" class="addToWishList flash-wishlist-pill" data-id="{{ $item->id }}" title="@lang('Add to wishlist')">
                                    @if (auth()->check() && $item->wishlists->count() > 0)
                                        <i class="fas fa-heart text-danger"></i>
                                    @else
                                        <i class="far fa-heart"></i>
                                    @endif
                                </button>

                                <a href="javascript:void(0)" class="getCoupon flash-card-link"
                                    data-id="{{ $item->id }}" data-title="{{ $item->title }}"
                                    data-code="{{ $item->code }}" data-description="{{ $item->description }}"
                                    data-link="{{ $item->link }}">
                                    <div class="flash-deal-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.15 + ($index * 0.08) }}s">
                                        <div class="flash-card-inner">
                                            {{-- Left Column: Brand, Title, Category, Cashback & Grab Button --}}
                                            <div class="flash-card-body">
                                                <div class="flash-card-brand-pill">
                                                    @if($item->store && $item->store->image)
                                                        <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" alt="{{ @$item->store->name }}">
                                                    @elseif($item->store)
                                                        <span class="flash-brand-text">{{ Str::limit(@$item->store->name, 12) }}</span>
                                                    @else
                                                        <span class="flash-brand-text">{{ Str::limit(@$item->category->name ?? 'DEAL', 12) }}</span>
                                                    @endif
                                                </div>

                                                <div class="flash-card-texts">
                                                    <span class="flash-card-category">
                                                        {{ Str::limit(@$item->category->name ?? 'Exclusive Offer', 24) }}
                                                    </span>
                                                    <h3 class="flash-card-title">
                                                        {{ Str::limit(__($item->title), 36) }}
                                                    </h3>
                                                </div>

                                                <div class="flash-card-footer">
                                                    <div class="flash-card-cashback">
                                                        @if($item->is_cashback == 1)
                                                            Flat {{ $item->cashback_amount }}% Cashback
                                                        @else
                                                            Flat Deal Offer
                                                        @endif
                                                    </div>
                                                    <span class="flash-grab-deal-btn">
                                                        @lang('Grab Deal')
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Right Column: Product / Offer Visual Image --}}
                                            <div class="flash-card-visual">
                                                @if($item->image)
                                                    <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" alt="{{ __($item->title) }}">
                                                @elseif($item->store && $item->store->image)
                                                    <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" alt="{{ @$item->store->name }}">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- View All Deals Button --}}
        <div class="row pt-4">
            <div class="col-12 text-center">
                <a href="{{ route('latest.coupons') }}" class="flash-view-all-btn">
                    @lang('View All Deals') <i class="fas fa-chevron-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* ========================================================
   CASHKARO FLASH DEALS STYLES
   ======================================================== */
.flash-deals-section {
    position: relative;
    background-color: #0f172a;
    padding: 55px 0 60px;
    overflow: hidden;
}

.flash-deals-bg-video {
    position: absolute;
    top: 50%;
    left: 50%;
    min-width: 100%;
    min-height: 100%;
    width: 100%;
    height: 100%;
    transform: translate(-50%, -50%);
    object-fit: cover;
    z-index: 0;
    pointer-events: none;
}

.flash-deals-bg-overlay {
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 50% 25%, rgba(0, 0, 0, 0.2) 0%, rgba(15, 23, 42, 0.45) 55%, rgba(15, 23, 42, 0.75) 100%);
    pointer-events: none;
    z-index: 1;
}

/* Header */
.flash-deals-header {
    margin-bottom: 24px;
}

.flash-deals-main-title {
    font-size: 38px;
    font-weight: 900;
    color: #ffffff !important;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 10px;
    line-height: 1.1;
    text-shadow: 0 4px 18px rgba(0, 0, 0, 0.45), 0 2px 4px rgba(0, 0, 0, 0.3);
}

.flash-deals-timer-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #e50914;
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
    padding: 6px 18px;
    border-radius: 6px;
    box-shadow: 0 4px 14px rgba(229, 9, 20, 0.45);
    letter-spacing: 0.4px;
}

.flash-deals-timer-badge i {
    font-size: 14px;
}

/* Card Wrapper & Link */
.flash-deals-slider-wrap {
    position: relative;
    padding: 0 10px;
}

.flash-card-link {
    text-decoration: none !important;
    display: block;
    color: inherit;
}

.flash-deal-card-wrapper {
    margin: 10px 8px;
}

/* CashKaro Ice-Blue Rounded Card */
.flash-deal-card {
    background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 45%, #ecfeff 100%);
    border-radius: 20px;
    padding: 20px 22px;
    height: 215px;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.9);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.flash-deal-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 32px -4px rgba(0, 0, 0, 0.22);
}

.flash-card-inner {
    display: flex;
    height: 100%;
    justify-content: space-between;
    position: relative;
}

/* Left Body */
.flash-card-body {
    flex: 1 1 58%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    z-index: 2;
    padding-right: 10px;
}

.flash-card-brand-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    padding: 5px 12px;
    border-radius: 8px;
    height: 36px;
    width: fit-content;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.flash-card-brand-pill img {
    max-height: 22px;
    max-width: 80px;
    object-fit: contain;
}

.flash-brand-text {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.flash-card-texts {
    margin-top: auto;
    margin-bottom: auto;
    padding: 6px 0;
}

.flash-card-category {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    line-height: 1.3;
    margin-bottom: 2px;
}

.flash-card-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a !important;
    line-height: 1.25;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Footer (Cashback + Grab Deal Button) */
.flash-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding-top: 6px;
}

.flash-card-cashback {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    white-space: nowrap;
}

.flash-grab-deal-btn {
    background: #2563eb;
    color: #ffffff !important;
    font-size: 12px;
    font-weight: 700;
    padding: 7px 16px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
    transition: background 0.2s ease, transform 0.2s ease;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
}

.flash-deal-card:hover .flash-grab-deal-btn {
    background: #1d4ed8;
    transform: scale(1.03);
}

/* Right Visual */
.flash-card-visual {
    flex: 0 0 42%;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    position: relative;
    z-index: 1;
}

.flash-card-visual img {
    max-width: 130px;
    max-height: 140px;
    object-fit: contain;
    filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.1));
    transition: transform 0.3s ease;
}

.flash-deal-card:hover .flash-card-visual img {
    transform: scale(1.06);
}

/* Wishlist Floating Pill */
.flash-wishlist-pill {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    font-size: 14px;
    z-index: 10;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.2s ease;
    cursor: pointer;
}

.flash-wishlist-pill:hover {
    background: #ffffff;
    color: #ef4444;
    transform: scale(1.1);
}

/* View All Deals Button */
.flash-view-all-btn {
    display: inline-flex;
    align-items: center;
    color: #ffffff !important;
    background: rgba(255, 255, 255, 0.16);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.35);
    padding: 9px 26px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none !important;
    letter-spacing: 0.3px;
    transition: all 0.25s ease;
}

.flash-view-all-btn:hover {
    background: #ffffff;
    color: #0f172a !important;
    box-shadow: 0 4px 16px rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}

/* Slick Custom Arrows for Flash Deals */
.flash-deals-slider-wrap .slick-prev,
.flash-deals-slider-wrap .slick-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    background: #ffffff !important;
    border-radius: 50%;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2) !important;
    z-index: 10;
    color: #0f172a !important;
    font-size: 0;
    display: flex !important;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    border: none !important;
    transition: all 0.2s ease;
}

.flash-deals-slider-wrap .slick-prev {
    left: -12px;
}

.flash-deals-slider-wrap .slick-next {
    right: -12px;
}

.flash-deals-slider-wrap .slick-prev:hover,
.flash-deals-slider-wrap .slick-next:hover {
    background: #f8fafc !important;
    transform: translateY(-50%) scale(1.08);
}

.flash-deals-slider-wrap .slick-prev::before {
    content: '\f053';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    font-size: 16px;
    color: #0f172a;
}

.flash-deals-slider-wrap .slick-next::before {
    content: '\f054';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    font-size: 16px;
    color: #0f172a;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .flash-deals-main-title {
        font-size: 28px;
    }
    .flash-deal-card {
        height: 195px;
        padding: 16px 16px;
    }
    .flash-card-title {
        font-size: 16px;
    }
    .flash-card-visual img {
        max-width: 105px;
        max-height: 115px;
    }
}
</style>

@push('script')
<script>
    (function($) {
        "use strict";

        // Slick Slider initialization for Flash Deals
        if ($('.ck-latest-slider').length > 0) {
            $('.ck-latest-slider').slick({
                dots: false,
                infinite: false,
                speed: 350,
                slidesToShow: 3.2,
                slidesToScroll: 1,
                arrows: true,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 2.5,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2.1,
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1.15,
                            arrows: false
                        }
                    }
                ]
            });
        }

        // Wishlist AJAX handler
        $(document).off('click', '.addToWishList').on('click', '.addToWishList', function(e) {
            e.preventDefault();
            e.stopPropagation();
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
                            if (typeof Toast !== 'undefined') {
                                Toast.fire({
                                    icon: 'success',
                                    title: response.message
                                });
                            }
                            var heartIcon = button.find('i');
                            heartIcon.removeClass('far fa-heart').addClass('fas fa-heart text-danger');
                        } else {
                            if (typeof Toast !== 'undefined') {
                                Toast.fire({
                                    icon: 'warning',
                                    title: response.error
                                });
                            }
                        }
                    },
                    error: function() {
                        if (typeof Toast !== 'undefined') {
                            Toast.fire({
                                icon: 'error',
                                title: 'Error occurred while adding to wishlist.'
                            });
                        }
                    }
                });
            } else {
                if (typeof Toast !== 'undefined') {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Please log in to add items to your wishlist.'
                    });
                }
            }
        });

        // Flash Deal Live Countdown Timer
        (function initFlashTimer() {
            var timerEl = document.getElementById('flashDealCountdownTimer');
            if (!timerEl) return;

            // Target time: today midnight or rolling 3-hour window
            var now = new Date();
            var target = new Date();
            target.setHours(target.getHours() + 2);
            target.setMinutes(target.getMinutes() + 45);
            target.setSeconds(target.getSeconds() + 39);

            function updateTimer() {
                var current = new Date();
                var diff = Math.max(0, Math.floor((target - current) / 1000));
                var hours = Math.floor(diff / 3600);
                var minutes = Math.floor((diff % 3600) / 60);
                var seconds = diff % 60;

                var hStr = hours < 10 ? '0' + hours : hours;
                var mStr = minutes < 10 ? '0' + minutes : minutes;
                var sStr = seconds < 10 ? '0' + seconds : seconds;

                timerEl.textContent = hStr + ':' + mStr + ':' + sStr;
            }

            updateTimer();
            setInterval(updateTimer, 1000);
        })();

    })(jQuery);
</script>
@endpush
