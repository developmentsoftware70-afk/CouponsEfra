@php
use App\Models\DealCategory;

// Moved filters into the model relationship
$categoryWiseDeals = DealCategory::where('status', 1)
    ->where('is_show', 1)
    ->with('deals')
    ->get();
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
                                            <a href="#" class="getDeal premium-deal-card"
                                                data-id="{{ $item->id }}"
                                                data-title="{{ $item->title }}"
                                                data-description="{{ $item->description }}"
                                                data-link="{{ $item->link }}"
                                                data-button_label="{{ $item->button_label ?? __('Grab Deal') }}"
                                                style="margin-bottom: 15px;">
                                                <img loading="lazy" src="{{ getImage(getFilePath('deal') . '/' . $item->image) }}" class="img-fluid" alt="">
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
