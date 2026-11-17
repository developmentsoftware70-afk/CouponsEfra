@php
    $topBrandCoupons = \Illuminate\Support\Facades\Cache::remember('home_top_brands_coupons_32', 600, function() {
        return App\Models\Coupon::with(['category', 'store', 'wishlists'])
            ->where('status', 1)
            ->where(function($q) {
                $q->where('is_top_brand', 1)
                  ->orWhere('is_exclusive', 1)
                  ->orWhere('is_popular', 1)
                  ->orWhere('is_featured', 1);
            })
            ->whereNotNull('id')
            ->orderByDesc('priority')
            ->orderByDesc('click_count')
            ->latest()
            ->limit(32)
            ->get();
    });

    $featuredCouponsData = $topBrandCoupons->slice(0, 16);
    $exlusivesCouponsData = $topBrandCoupons->slice(16, 16);
    if ($exlusivesCouponsData->isEmpty() && $featuredCouponsData->count() > 4) {
        $half = (int) ceil($featuredCouponsData->count() / 2);
        $exlusivesCouponsData = $featuredCouponsData->slice($half);
        $featuredCouponsData = $featuredCouponsData->slice(0, $half);
    }

    $ckTheme = [
        ['bg' => 'linear-gradient(135deg, #ff9900, #ff5500)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
        ['bg' => 'linear-gradient(135deg, #2874f0, #0053c8)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
        ['bg' => 'linear-gradient(135deg, #c74c10, #802802)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
        ['bg' => 'linear-gradient(135deg, #00c6ff, #0072ff)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
        ['bg' => 'linear-gradient(135deg, #f093fb, #f5576c)', 'btn' => '#0053c8', 'badge' => '#ffd700']
    ];
@endphp

@push('style')
<style>
    /* Top Brands Section & Header */
    .top-brand-slider {
        position: relative;
    }
    .top-brands-header-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }
    .pop-brands-view-all {
        font-size: 14px;
        font-weight: 600;
        color: #0047ff !important;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
        padding: 4px 8px;
        border-radius: 6px;
    }
    .pop-brands-view-all:hover {
        color: #0036cc !important;
        transform: translateX(3px);
    }
    .pop-brands-view-all i {
        font-size: 12px;
        transition: transform 0.25s ease;
    }
    .pop-brands-view-all:hover i {
        transform: translateX(3px);
    }
</style>
@endpush

<section class="exclusive-section py-20 bg-white" id="top-brands-section">
    <div class="container-fluid container-custom">
        {{-- Section Header: Top Brands with View All linking to all brands --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="top-brands-header-wrap">
                    <h2 class="section-title mb-0 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                        @lang('Top Brands')
                    </h2>
                    <a href="{{ route('brands') }}" class="pop-brands-view-all">
                        @lang('View All') <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Row 1: Popular Brand Coupons Slider (No <> buttons, manual drag/swipe) --}}
        @if($featuredCouponsData && $featuredCouponsData->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="slider-wrapper">
                    <div class="top-brand-slider">
                        @foreach ($featuredCouponsData as $index => $item)
                            @php
                                $theme = $ckTheme[3];
                            @endphp
                            <div class="slick-slide-inner">
                                <a href="{{ route('coupon.details', $item->slug ?? $item->id) }}" class="getCoupon ck-style-card-link"
                                    data-id="{{ $item->id }}" data-slug="{{ $item->slug }}">
                                    @if(isset($item->is_background) && $item->is_background == 0 && $item->image)
                                        <div class="ck-style-card ck-banner-only-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                                            <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" alt="{{ __($item->title) }}" class="ck-banner-only-img" loading="lazy">
                                            <button class="addToWishList" data-id="{{ $item->id }}" style="position:absolute; top:12px; right:12px; background:rgba(0,0,0,0.4); color:#fff; border:none; border-radius:50%; width:30px; height:30px; z-index:5;">
                                                @if (auth()->check() && $item->wishlists->count() > 0)
                                                    <i class="fas fa-heart text-danger"></i>
                                                @else
                                                    <i class="far fa-heart"></i>
                                                @endif
                                            </button>
                                            <span class="flash-banner-grab-deal-btn">
                                                @lang('Grab Deal')
                                            </span>
                                        </div>
                                    @else
                                        <div class="ck-style-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s" style="background: {{ $theme['bg'] }};">
                                            <div class="ck-card-left">
                                                <h3 class="ck-title">{{ __($item->title) }}</h3>
                                                <p class="ck-subtitle">{{ Str::limit(html_entity_decode(strip_tags($item->description)), 40) }}</p>
                                                <div class="ck-btn-wrap">
                                                    <span class="ck-btn">
                                                        @lang('Grab Deal')
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ck-card-right">
                                                <button class="addToWishList" data-id="{{ $item->id }}" style="position:absolute; top:20px; left:0; background:rgba(255,255,255,0.2); color:#fff; border:none; border-radius:50%; width:30px; height:30px; z-index:5;">
                                                    @if (auth()->check() && $item->wishlists->count() > 0)
                                                        <i class="fas fa-heart"></i>
                                                    @else
                                                        <i class="far fa-heart"></i>
                                                    @endif
                                                </button>
                                                <div class="ck-badge" style="background: {{ $theme['badge'] }}; color: #000;">
                                                    @if($item->is_cashback == 1)
                                                        {{ $item->cashback_amount }}@lang('% Cash Back')
                                                    @else 
                                                        @lang('New')
                                                    @endif
                                                </div>
                                                <div class="ck-image-wrap">
                                                    @if($item->image)
                                                    <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" alt="@lang('Coupon Image')" loading="lazy">
                                                    @elseif($item->store && $item->store->image)
                                                    <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" alt="" loading="lazy">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Row 2: Exclusive Brand Coupons Slider (No <> buttons, manual drag/swipe) --}}
        @if($exlusivesCouponsData && $exlusivesCouponsData->count() > 0)
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="slider-wrapper">
                    <div class="top-brand-slider">
                        @foreach ($exlusivesCouponsData as $index => $item)
                            @php
                                $theme = $ckTheme[0];
                            @endphp
                            <div class="slick-slide-inner">
                                <a href="{{ route('coupon.details', $item->slug ?? $item->id) }}" class="getCoupon ck-style-card-link"
                                    data-id="{{ $item->id }}" data-slug="{{ $item->slug }}">
                                    @if(isset($item->is_background) && $item->is_background == 0 && $item->image)
                                        <div class="ck-style-card ck-banner-only-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                                            <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" alt="{{ __($item->title) }}" class="ck-banner-only-img" loading="lazy">
                                            <button class="addToWishList" data-id="{{ $item->id }}" style="position:absolute; top:12px; right:12px; background:rgba(0,0,0,0.4); color:#fff; border:none; border-radius:50%; width:30px; height:30px; z-index:5;">
                                                @if (auth()->check() && $item->wishlists->count() > 0)
                                                    <i class="fas fa-heart text-danger"></i>
                                                @else
                                                    <i class="far fa-heart"></i>
                                                @endif
                                            </button>
                                            <span class="flash-banner-grab-deal-btn">
                                                @lang('Grab Deal')
                                            </span>
                                        </div>
                                    @else
                                        <div class="ck-style-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s" style="background: {{ $theme['bg'] }};">
                                            <div class="ck-card-left">
                                                <h3 class="ck-title">{{ __($item->title) }}</h3>
                                                <p class="ck-subtitle">{{ Str::limit(html_entity_decode(strip_tags($item->description)), 40) }}</p>
                                                <div class="ck-btn-wrap">
                                                    <span class="ck-btn">
                                                        @lang('Grab Deal')
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ck-card-right">
                                                <button class="addToWishList" data-id="{{ $item->id }}" style="position:absolute; top:20px; left:0; background:rgba(255,255,255,0.2); color:#fff; border:none; border-radius:50%; width:30px; height:30px; z-index:5;">
                                                    @if (auth()->check() && $item->wishlists->count() > 0)
                                                        <i class="fas fa-heart"></i>
                                                    @else
                                                        <i class="far fa-heart"></i>
                                                    @endif
                                                </button>
                                                <div class="ck-badge" style="background: {{ $theme['badge'] }}; color: #000;">
                                                    @if($item->is_cashback == 1)
                                                        {{ $item->cashback_amount }}@lang('% Cash Back')
                                                    @else 
                                                        @lang('New')
                                                    @endif
                                                </div>
                                                <div class="ck-image-wrap">
                                                    @if($item->image)
                                                    <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" alt="@lang('Coupon Image')" loading="lazy">
                                                    @elseif($item->store && $item->store->image)
                                                    <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" alt="" loading="lazy">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Bottom View All CTA --}}
        <div class="row pt-3">
            <div class="col-lg-12 text-center">
                <a href="{{ route('brands') }}" class="pop-brands-view-all fs-14 fw-bold">
                    @lang('View All Brands') <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@push('script')
<script>
    (function($) {
        "use strict";
        // Initialize Top Brands sliders with NO <> arrow buttons (clean drag & swipe)
        if ($('.top-brand-slider').length > 0) {
            $('.top-brand-slider').each(function() {
                var $this = $(this);
                if (!$this.hasClass('slick-initialized')) {
                    $this.slick({
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        autoplay: false,
                        infinite: true,
                        draggable: true,
                        swipe: true,
                        swipeToSlide: true,
                        touchMove: true,
                        dots: false,
                        arrows: false, // <> Buttons completely removed as requested
                        responsive: [
                            {
                                breakpoint: 1199,
                                settings: {
                                    slidesToShow: 4,
                                    arrows: false
                                }
                            },
                            {
                                breakpoint: 991,
                                settings: {
                                    slidesToShow: 3,
                                    arrows: false
                                }
                            },
                            {
                                breakpoint: 767,
                                settings: {
                                    slidesToShow: 2,
                                    arrows: false
                                }
                            },
                            {
                                breakpoint: 424,
                                settings: {
                                    slidesToShow: 1.2,
                                    arrows: false
                                }
                            }
                        ]
                    });
                }
            });
        }
    })(jQuery);
</script>
@endpush
