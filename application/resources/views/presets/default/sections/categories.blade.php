@php
    $popularCoupon = getContent('categories.content', true);
    $popularCouponElements = \Illuminate\Support\Facades\Cache::remember('home_categories_elements_v3', 1800, function() {
        return App\Models\Category::with(['coupons' => function($q) {
            $q->where('status', 1);
        }])->where('show_on_front', 1)->where('status', 1)->latest()->limit(16)->get();
    });
@endphp

@push('style')
<style>
    /* Top Categories Section Wrapper */
    .top-categories-section {
        padding-top: 24px;
        padding-bottom: 20px;
        margin-top: 10px;
        margin-bottom: 15px;
        position: relative;
    }

    /* Carousel Wrapper */
    .cat-carousel-wrap {
        position: relative;
        padding: 8px 0;
        margin-top: 15px;
    }
    .top-category-carousel {
        margin: 0 -8px;
    }

    /* Prevent clipping on hover inside Slick Slider */
    .top-category-carousel .slick-list {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
        margin-top: -18px !important;
        margin-bottom: -18px !important;
    }

    /* Category Item & Card */
    .cat-squircle-item {
        padding: 6px 8px 10px;
        text-align: center;
        outline: none !important;
    }
    .cat-squircle-card {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        cursor: pointer;
        outline: none !important;
        transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    /* Option 1: Modern Squircle Avatar - Uniform Premium Ice-Blue Background */
    .cat-squircle-avatar {
        width: 86px;
        height: 86px;
        border-radius: 20px;
        background: linear-gradient(145deg, #f4f8ff 0%, #e6f0ff 100%) !important;
        border: 1.5px solid #d4e4fc;
        box-shadow: 0 4px 14px rgba(0, 71, 255, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
    }
    .cat-squircle-card:hover .cat-squircle-avatar {
        background: linear-gradient(145deg, #e4efff 0%, #d4e5ff 100%) !important;
        transform: translateY(-4px);
        border-color: #3b82f6;
        box-shadow: 0 12px 26px rgba(0, 71, 255, 0.16);
    }
    .cat-squircle-avatar img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        mix-blend-mode: multiply;
        transition: transform 0.3s ease;
    }
    .cat-squircle-card:hover .cat-squircle-avatar img {
        transform: scale(1.08);
    }

    /* Category Name Label */
    .cat-squircle-label {
        margin-top: 10px;
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        text-align: center;
        line-height: 1.25;
        max-width: 95px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        transition: color 0.25s ease;
    }
    .cat-squircle-card:hover .cat-squircle-label {
        color: #0047ff;
    }

    /* Sleek Floating Next/Prev Arrow Buttons */
    .cat-carousel-wrap .slick-arrow {
        position: absolute;
        top: 42%;
        transform: translateY(-50%);
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08) !important;
        color: #1e293b !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
        z-index: 10;
        cursor: pointer;
        transition: all 0.25s ease;
        padding: 0;
        outline: none !important;
    }
    .cat-carousel-wrap .slick-prev {
        left: -14px;
    }
    .cat-carousel-wrap .slick-next {
        right: -14px;
    }
    .cat-carousel-wrap .slick-arrow:hover {
        background: #0047ff !important;
        border-color: #0047ff !important;
        color: #ffffff !important;
        box-shadow: 0 6px 18px rgba(0, 71, 255, 0.3) !important;
        transform: translateY(-50%) scale(1.06);
    }
    .cat-carousel-wrap .slick-arrow.slick-disabled {
        opacity: 0;
        pointer-events: none;
    }
    .cat-carousel-wrap .slick-arrow i {
        font-size: 13px !important;
        line-height: 1;
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        .cat-squircle-avatar {
            width: 76px;
            height: 76px;
            border-radius: 18px;
            padding: 13px;
        }
    }
    @media (max-width: 767px) {
        .top-categories-section {
            padding-top: 18px;
            padding-bottom: 14px;
        }
        .cat-squircle-avatar {
            width: 68px;
            height: 68px;
            border-radius: 16px;
            padding: 11px;
        }
        .cat-squircle-label {
            font-size: 12.5px;
            max-width: 80px;
            margin-top: 8px;
        }
        .cat-carousel-wrap .slick-prev {
            left: -6px;
            width: 32px;
            height: 32px;
        }
        .cat-carousel-wrap .slick-next {
            right: -6px;
            width: 32px;
            height: 32px;
        }
    }
    @media (max-width: 480px) {
        .cat-squircle-avatar {
            width: 62px;
            height: 62px;
            border-radius: 14px;
            padding: 10px;
        }
        .cat-squircle-label {
            font-size: 11.5px;
            max-width: 72px;
        }
    }
</style>
@endpush

<section class="top-categories-section">
    <div class="container-fluid container-custom">
        {{-- Section Header (Site Standard) --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            {{ __($popularCoupon->data_values->heading) }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Option 1: Modern Squircle Categories Carousel --}}
        <div class="cat-carousel-wrap">
            <div class="top-category-carousel">
                @foreach($popularCouponElements as $item)
                <div class="cat-squircle-item">
                    <a href="{{ route('category.coupons', $item->id) }}" class="cat-squircle-card" title="{{ __($item->name) }}">
                        <div class="cat-squircle-avatar">
                            <img src="{{ getImage(getFilePath('category') . '/' . $item->image) }}" alt="{{ __($item->name) }}" loading="lazy">
                        </div>
                        <span class="cat-squircle-label">
                            {{ __($item->name) }}
                        </span>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Bottom View More Link (Consistent with other sections) --}}
        <div class="row pt-2">
            <div class="col-lg-12 text-end">
                <a href="{{ route('categories') }}" class="view-more-link">@lang('View More')</a>
            </div>
        </div>
    </div>
</section>

@push('script')
<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            var $slider = $('.top-category-carousel');
            if ($slider.length && typeof $.fn.slick === 'function') {
                if ($slider.hasClass('slick-initialized')) {
                    $slider.slick('unslick');
                }
                var totalItems = $slider.find('.cat-squircle-item').length;
                $slider.slick({
                    slidesToShow: 8,
                    slidesToScroll: 2,
                    autoplay: false,
                    infinite: totalItems > 8,
                    pauseOnHover: true,
                    pauseOnFocus: true,
                    draggable: true,
                    swipe: true,
                    swipeToSlide: true,
                    touchMove: true,
                    dots: false,
                    arrows: true,
                    prevArrow: '<button type="button" class="slick-prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>',
                    responsive: [
                        {
                            breakpoint: 1399,
                            settings: {
                                slidesToShow: 7,
                                slidesToScroll: 2,
                                infinite: totalItems > 7
                            }
                        },
                        {
                            breakpoint: 1199,
                            settings: {
                                slidesToShow: 6,
                                slidesToScroll: 2,
                                infinite: totalItems > 6
                            }
                        },
                        {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: 5,
                                slidesToScroll: 2,
                                infinite: totalItems > 5
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 4,
                                slidesToScroll: 2,
                                infinite: totalItems > 4
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 3.5,
                                slidesToScroll: 1,
                                arrows: false,
                                infinite: false
                            }
                        }
                    ]
                });

                // Horizontal wheel scroll support
                $slider.on('wheel', function(e) {
                    var raw = e.originalEvent;
                    if (!raw) return;
                    var absX = Math.abs(raw.deltaX || 0);
                    var absY = Math.abs(raw.deltaY || 0);
                    if (raw.shiftKey || (absX > absY && absX > 15)) {
                        e.preventDefault();
                        var delta = raw.shiftKey ? raw.deltaY : raw.deltaX;
                        if (delta > 0) {
                            $(this).slick('slickNext');
                        } else if (delta < 0) {
                            $(this).slick('slickPrev');
                        }
                    }
                });
            }
        });
    })(jQuery);
</script>
@endpush
