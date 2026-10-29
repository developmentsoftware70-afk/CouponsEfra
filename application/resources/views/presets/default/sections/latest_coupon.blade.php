@php
    $latestCoupon = getContent('latest_coupon.content', true);
    $latestCouponsData = \Illuminate\Support\Facades\Cache::remember('home_latest_coupons_12', 600, function() {
        return App\Models\Coupon::with(['category', 'store', 'wishlists'])
            ->where('status', 1)
            ->where('is_cashback', 0)
            ->latest()
            ->limit(12)
            ->get();
    });
@endphp

<section class="flash-deals-section" id="latest-coupons-section">
    {{-- Soft Atmospheric Cloud Sky Overlay --}}
    <div class="flash-deals-cloud-overlay"></div>

    {{-- Subtle, Ultra-Slow Falling Snow Canvas (Light & Slow) --}}
    <canvas id="flashRainCanvas" class="flash-rain-canvas"></canvas>
    <div class="container-fluid container-custom position-relative" style="z-index: 2;">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content" style="margin-bottom: 20px;">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            {{ __($latestCoupon->data_values->heading) }}
                        </h2>
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

                                <a href="{{ route('coupon.details', $item->slug ?? $item->id) }}" class="getCoupon flash-card-link"
                                    data-id="{{ $item->id }}" data-slug="{{ $item->slug }}">
                                    @if(isset($item->is_background) && $item->is_background == 0 && $item->image)
                                        <div class="flash-deal-card flash-banner-only-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.15 + ($index * 0.08) }}s">
                                            <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" alt="{{ __($item->title) }}" class="flash-banner-only-img" loading="lazy">
                                            <span class="flash-banner-grab-deal-btn">
                                                @lang('Grab Deal')
                                            </span>
                                        </div>
                                    @else
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
                                                        <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" alt="{{ __($item->title) }}" loading="lazy">
                                                    @elseif($item->store && $item->store->image)
                                                        <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" alt="{{ @$item->store->name }}" loading="lazy">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- View More Link at Bottom Right --}}
        <div class="row pt-2">
            <div class="col-lg-12 text-end">
                <a href="{{ route('latest.coupons') }}" class="view-more-link">@lang('View More')</a>
            </div>
        </div>
    </div>
</section>

<style>
/* ========================================================
   CASHKARO COUPONS SECTION STYLES
   ======================================================== */
.flash-deals-section {
    position: relative;
    background: #f1f5f9 url('{{ asset("assets/presets/default/images/bg/cloud-sky-bg.jpg") }}') center center / cover no-repeat;
    padding: 35px 0 40px;
    overflow: hidden;
    border-top: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
}

.flash-deals-cloud-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(255, 255, 255, 0.5) 0%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.45) 100%);
    pointer-events: none;
    z-index: 0;
}

.flash-rain-canvas {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
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
    background: transparent !important;
    box-shadow: none !important;
}

/* CashKaro Dark Sleek Card - High Contrast on Light Cloudy Sky (No Shadow) */
.flash-deal-card {
    background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
    border-radius: 20px;
    padding: 20px 20px;
    height: 235px;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: none !important;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease;
}

.flash-deal-card:hover {
    transform: translateY(-5px);
    border-color: rgba(56, 189, 248, 0.5);
    box-shadow: none !important;
}

/* Full Banner Image Only Card (When Background is OFF) */
.flash-banner-only-card {
    background: transparent !important;
    padding: 0 !important;
    border: none !important;
    overflow: hidden !important;
    border-radius: 20px !important;
}

.flash-banner-only-card .flash-banner-only-img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    display: block !important;
    border-radius: 20px !important;
    transition: transform 0.3s ease !important;
}

.flash-banner-only-card:hover .flash-banner-only-img {
    transform: scale(1.02);
}

.flash-banner-grab-deal-btn {
    position: absolute;
    bottom: 20px;
    right: 20px;
    z-index: 4;
    background: #2563eb;
    color: #ffffff !important;
    font-size: 11.5px;
    font-weight: 700;
    padding: 6px 13px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    transition: background 0.2s ease, transform 0.2s ease;
    pointer-events: none;
}

.flash-deal-card:hover .flash-banner-grab-deal-btn {
    background: #1d4ed8;
    transform: scale(1.03);
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
}

@media (max-width: 768px) {
    .flash-banner-grab-deal-btn {
        bottom: 12px;
        right: 12px;
        font-size: 11.5px;
        padding: 5px 14px;
    }
}

.flash-card-inner {
    display: flex;
    height: 100%;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

/* Left Body */
.flash-card-body {
    flex: 1 1 auto;
    min-width: 0;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    z-index: 2;
    padding-right: 12px;
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
    box-shadow: none !important;
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
    min-width: 0;
}

.flash-card-category {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #94a3b8;
    line-height: 1.3;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.flash-card-title {
    font-size: 18px;
    font-weight: 800;
    color: #ffffff !important;
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
    gap: 6px;
    padding-top: 6px;
    min-width: 0;
}

.flash-card-cashback {
    font-size: 12.5px;
    font-weight: 800;
    color: #38bdf8;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    min-width: 0;
}

.flash-grab-deal-btn {
    background: #2563eb;
    color: #ffffff !important;
    font-size: 11.5px;
    font-weight: 700;
    padding: 6px 13px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
    flex-shrink: 0;
    transition: background 0.2s ease, transform 0.2s ease;
    box-shadow: none !important;
}

.flash-deal-card:hover .flash-grab-deal-btn {
    background: #1d4ed8;
    transform: scale(1.03);
    box-shadow: none !important;
}

/* Right Visual - Pure Image only, No White Box Background */
.flash-card-visual {
    width: 115px;
    height: 78px;
    flex: 0 0 115px;
    max-width: 115px;
    max-height: 78px;
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 !important;
    margin-top: auto;
    margin-bottom: auto;
    position: relative;
    z-index: 1;
    flex-shrink: 0;
    box-sizing: border-box;
    overflow: hidden;
    border-radius: 12px;
}

.flash-card-visual img {
    width: 100%;
    height: 100%;
    max-width: 115px;
    max-height: 78px;
    object-fit: cover;
    border-radius: 12px;
    display: block;
    filter: none !important;
    transition: transform 0.3s ease;
}

.flash-deal-card:hover .flash-card-visual img {
    transform: scale(1.05);
}

/* Wishlist Floating Pill */
.flash-wishlist-pill {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.95);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0f172a;
    font-size: 14px;
    z-index: 10;
    box-shadow: none !important;
    transition: all 0.2s ease;
    cursor: pointer;
}

.flash-wishlist-pill:hover {
    background: #ffffff;
    color: #ef4444;
    transform: scale(1.1);
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
    font-size: 16px;
    display: flex !important;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    border: 1.5px solid #e2e8f0 !important;
    transition: all 0.2s ease;
}

.flash-deals-slider-wrap .slick-prev {
    left: -14px;
}

.flash-deals-slider-wrap .slick-next {
    right: -14px;
}

.flash-deals-slider-wrap .slick-prev:hover,
.flash-deals-slider-wrap .slick-next:hover {
    background: #f8fafc !important;
    transform: translateY(-50%) scale(1.08);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25) !important;
    color: #0053c8 !important;
}

.flash-deals-slider-wrap .slick-prev i,
.flash-deals-slider-wrap .slick-next i {
    font-size: 16px !important;
    color: #0f172a !important;
    display: inline-block !important;
}

@media (max-width: 768px) {
    .flash-deals-slider-wrap .slick-prev {
        left: 2px;
        width: 36px;
        height: 36px;
    }
    .flash-deals-slider-wrap .slick-next {
        right: 2px;
        width: 36px;
        height: 36px;
    }
    .flash-deals-slider-wrap .slick-prev i,
    .flash-deals-slider-wrap .slick-next i {
        font-size: 13px !important;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .flash-deals-main-title {
        font-size: 28px;
    }
    .flash-deal-card {
        height: 210px;
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
                prevArrow: '<button type="button" class="slick-prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>',
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 2.5,
                            arrows: true
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2.1,
                            arrows: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1.15,
                            arrows: true
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

        // Subtle & Ultra-Slow Falling Snow Particles (Atmospheric Cloud Effect)
        (function initSnowfall() {
            var canvas = document.getElementById('flashRainCanvas');
            if (!canvas) return;
            var ctx = canvas.getContext('2d');
            var section = document.getElementById('latest-coupons-section');
            var flakes = [];
            var flakeCount = 36; // clean, subtle drift
            var isVisible = true;
            var animFrameId = null;

            function resize() {
                if (!section || !canvas) return;
                canvas.width = section.offsetWidth;
                canvas.height = section.offsetHeight;
            }
            resize();
            $(window).on('resize', resize);

            for (var i = 0; i < flakeCount; i++) {
                flakes.push({
                    x: Math.random() * (canvas.width || window.innerWidth),
                    y: Math.random() * (canvas.height || 420),
                    radius: 0.9 + Math.random() * 1.4,
                    speed: 0.2 + Math.random() * 0.35,
                    opacity: 0.18 + Math.random() * 0.22,
                    swing: Math.random() * Math.PI * 2,
                    swingSpeed: 0.008 + Math.random() * 0.012
                });
            }

            function animateSnow() {
                if (!isVisible) return;
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                for (var i = 0; i < flakes.length; i++) {
                    var f = flakes[i];
                    f.swing += f.swingSpeed;
                    f.x += Math.sin(f.swing) * 0.25;
                    f.y += f.speed;

                    ctx.beginPath();
                    ctx.arc(f.x, f.y, f.radius, 0, Math.PI * 2);
                    ctx.fillStyle = 'rgba(255, 255, 255, ' + f.opacity + ')';
                    ctx.fill();

                    if (f.y > canvas.height + 5) {
                        f.y = -5;
                        f.x = Math.random() * canvas.width;
                    }
                }
                animFrameId = requestAnimationFrame(animateSnow);
            }

            if ('IntersectionObserver' in window && section) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            if (!isVisible) {
                                isVisible = true;
                                animFrameId = requestAnimationFrame(animateSnow);
                            }
                        } else {
                            isVisible = false;
                            if (animFrameId) cancelAnimationFrame(animFrameId);
                        }
                    });
                }, { threshold: 0.05 });
                observer.observe(section);
            }

            animFrameId = requestAnimationFrame(animateSnow);
        })();
    })(jQuery);
</script>
@endpush

