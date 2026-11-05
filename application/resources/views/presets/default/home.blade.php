@extends($activeTemplate.'layouts.frontend')
@section('content')
    @php
        $sliders = (isset($slider) && $slider->isNotEmpty()) 
            ? $slider 
            : App\Models\Slider::where('status', '1')->latest()->get();
    @endphp
    <section class="full-width-slider">
        @foreach($sliders as $item)
        <div class="slider-item">
            <a href="{{ route('slider.redirect', $item->id) }}" class="slider-banner-link" target="_blank">
                <img src="{{ getImage(getFilePath('slider') . '/' . $item->image) }}" alt="{{ $item->title ?? 'Slider Image' }}" class="img-fluid">
            </a>
        </div>
        @endforeach
    </section>
    
    @php
        // Ordered section sequence as requested:
        // 1. Categories
        // 2. Deals (Noise, Neemans, Nilkamal)
        // 3. Coupons (Flash Deals)
        // 4. Popular Coupons
        // 5. Exclusive Coupons
        // 6. Pehla Ad Slider (top_ads) -> Cashback ke UPAR
        // 7. Cashback Coupons (cashback_coupons)
        // 8. Dusra Ad Slider (deal_coupon) -> Cashback ke NICHE
        // 9. Most Popular Brands (popular_brands) -> Testimonials ke UPAR
        // 10. Testimonials, About, Blog
        $desiredOrder = [
            'categories',
            'popular_deal',
            'latest_coupon',
            'feature_coupon',
            'exclusive_coupons',
            'top_ads',
            'cashback_coupons',
            'deal_coupon',
            'popular_brands',
            'testimonial',
            'about',
            'blog'
        ];

        $orderedSections = $desiredOrder;
    @endphp

    @foreach($orderedSections as $sec)
        @include($activeTemplate.'sections.'.$sec)
    @endforeach

@endsection
