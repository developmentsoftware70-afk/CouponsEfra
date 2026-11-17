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
        // 4. Top Brands (Popular Coupons + Exclusive Coupons combined in one)
        // 5. Pehla Ad Slider (top_ads) -> Cashback ke UPAR
        // 6. Cashback Coupons (cashback_coupons)
        // 7. Dusra Ad Slider (deal_coupon) -> Cashback ke NICHE
        // 8. Most Popular Brands (popular_brands) -> Testimonials ke UPAR
        // 9. Testimonials, About, Blog
        $desiredOrder = [
            'categories',
            'popular_deal',
            'latest_coupon',
            'top_brand',
            'top_ads',
            'cashback_coupons',
            'deal_coupon',
            'popular_brands',
            'testimonial',
            'about',
            'blog'
        ];

        $orderedSections = $desiredOrder;

        // Append any active custom sections created by admin
        if (class_exists('App\Models\CustomSection')) {
            try {
                $activeCustomSections = App\Models\CustomSection::where('status', 1)->pluck('key')->all();
                foreach ($activeCustomSections as $cKey) {
                    if (!in_array($cKey, $orderedSections)) {
                        // Place custom section right above testimonials
                        $pos = array_search('testimonial', $orderedSections);
                        if ($pos !== false) {
                            array_splice($orderedSections, $pos, 0, [$cKey]);
                        } else {
                            $orderedSections[] = $cKey;
                        }
                    }
                }
            } catch (\Throwable $e) {}
        }
    @endphp

    @foreach($orderedSections as $sec)
        @if(str_starts_with($sec, 'custom_'))
            @include($activeTemplate.'sections.custom_section', ['secKey' => $sec])
        @elseif(view()->exists($activeTemplate.'sections.'.$sec))
            @include($activeTemplate.'sections.'.$sec)
        @endif
    @endforeach

@endsection
