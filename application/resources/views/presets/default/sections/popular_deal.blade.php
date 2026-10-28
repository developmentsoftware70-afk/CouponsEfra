    @php
use App\Models\DealCategory;

// Moved filters into the model relationship
$categoryWiseDeals = \Illuminate\Support\Facades\Cache::remember('home_category_wise_deals', 1800, function() {
    return DealCategory::where('status', 1)
        ->where('is_show', 1)
        ->with(['deals' => function($q) {
            $q->where('status', 1)->where('is_show', 1);
        }])
        ->get();
});
$bgClass = ['bg-light', 'bg-white'];
@endphp

@push('style')
<style>
    .premium-deal-card {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: none !important;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        display: block;
        border: 1px solid rgba(0, 0, 0, 0.03);
        background: #fff;
        margin: 15px 10px;
        position: relative;
    }
    .premium-deal-card:hover {
        transform: translateY(-10px);
        box-shadow: none !important;
    }
    .premium-deal-card img {
        width: 100%;
        height: auto;
        transition: transform 0.6s ease;
        display: block;
    }
    .premium-deal-card:hover img {
        transform: scale(1.05);
    }
    .premium-deal-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0) 50%, rgba(0,0,0,0.1) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
        pointer-events: none;
    }
    .premium-deal-card:hover::after {
        opacity: 1;
    }

    /* Slide Navigation Buttons for Deal Sliders (Neemans, Nilkamal, Noise Top Deals) */
    .deal-slider {
        position: relative;
    }
    .deal-slider .slick-prev,
    .deal-slider .slick-next {
        position: absolute !important;
        top: 50% !important;
        bottom: auto !important;
        transform: translateY(-50%) !important;
        width: 44px !important;
        height: 44px !important;
        background: #ffffff !important;
        border-radius: 50% !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.18) !important;
        border: 1.5px solid #e2e8f0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        z-index: 10 !important;
        opacity: 1 !important;
        visibility: visible !important;
        cursor: pointer !important;
        transition: all 0.25s ease !important;
        color: #0f172a !important;
    }
    .deal-slider .slick-prev {
        left: -14px !important;
        right: auto !important;
    }
    .deal-slider .slick-next {
        right: -14px !important;
        left: auto !important;
    }
    .deal-slider .slick-prev:hover,
    .deal-slider .slick-next:hover {
        background: #f8fafc !important;
        transform: translateY(-50%) scale(1.08) !important;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25) !important;
        border-color: #cbd5e1 !important;
        color: #0053c8 !important;
    }
    .deal-slider .slick-prev i,
    .deal-slider .slick-next i {
        font-size: 16px !important;
        color: #0f172a !important;
    }
    .deal-slider .slick-disabled {
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
        transition: opacity 0.2s ease, visibility 0.2s ease !important;
    }
    @media (max-width: 768px) {
        .deal-slider .slick-prev {
            left: -6px !important;
            width: 36px !important;
            height: 36px !important;
        }
        .deal-slider .slick-next {
            right: -6px !important;
            width: 36px !important;
            height: 36px !important;
        }
        .deal-slider .slick-prev i,
        .deal-slider .slick-next i {
            font-size: 13px !important;
        }
    }
</style>
@endpush

<section class="exclusive-section pb-20 pt-0 bg-white">
    <div class="container-fluid container-custom">
    @foreach($categoryWiseDeals as $index => $category)
        @if($category->deals->count())
            <div class="category-block mb-2">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-content">
                            <div class="title-wrap" style="margin-bottom: 5px;">
                                <h2 class="section-title wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                                    {{ $category->title }}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="slider-wrapper" style="margin-bottom: 0;">
                            <div class="deal-slider" style="margin-bottom: 0;">
                                @foreach ($category->deals as $item)
                                    @if($item->image)
                                    <div class="slick-slide-inner">
                                        <div class="p-0">
                                            <a href="{{ route('deal.details', $item->id) }}" class="premium-deal-card" style="margin-bottom: 15px;">
                                                <img loading="lazy" src="{{ getImage(getFilePath('deal') . '/' . $item->image) }}" class="img-fluid" alt="{{ $item->title }}">
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    </div>
</section>
