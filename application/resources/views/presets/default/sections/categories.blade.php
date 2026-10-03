@php
    $popularCoupon = getContent('categories.content', true);
    $popularCouponElements = App\Models\Category::with('coupons')->where('show_on_front', 1)->where('status', 1)->latest()->limit(8)->get();
@endphp
<section class="top-collections top-stores second py-20">
    <div class="container-fluid container-custom">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            {{__($popularCoupon->data_values->heading)}}
                        </h2>
                        {{-- 
                        <a href="{{route('categories')}}" class="view-more-link">@lang('View More')</a> --}}
                    </div>
                </div>
            </div>
        </div>


        @push('style')
        <style>
            .adv-cat-card {
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(20px);
                border-radius: 20px;
                padding: 20px;
                display: flex;
                align-items: center;
                gap: 20px;
                box-shadow: none;
                border: 1px solid rgba(255, 255, 255, 0.8);
                transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
                text-decoration: none !important;
                margin: 0;
                position: relative;
                overflow: hidden;
            }
            .store-slider .slick-slide-inner {
                padding: 15px 10px;
            }
            .adv-cat-card::before {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                opacity: 0.08;
                transition: opacity 0.4s ease;
                z-index: 0;
            }
            /* Subtle neon glow hints */
            .slick-slide-inner:nth-child(4n+1) .adv-cat-card::before { background: radial-gradient(circle at 0% 0%, #38bdf8, transparent 70%); }
            .slick-slide-inner:nth-child(4n+2) .adv-cat-card::before { background: radial-gradient(circle at 0% 0%, #f472b6, transparent 70%); }
            .slick-slide-inner:nth-child(4n+3) .adv-cat-card::before { background: radial-gradient(circle at 0% 0%, #a78bfa, transparent 70%); }
            .slick-slide-inner:nth-child(4n+4) .adv-cat-card::before { background: radial-gradient(circle at 0% 0%, #10b981, transparent 70%); }

            .adv-cat-card:hover {
                transform: translateY(-4px);
                box-shadow: none;
                border-color: rgba(255, 255, 255, 1);
            }
            .adv-cat-card:hover::before {
                opacity: 0.25;
            }
            
            .adv-cat-icon-wrap {
                width: 70px;
                height: 70px;
                border-radius: 50%;
                background: #ffffff;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: none;
                position: relative;
                z-index: 1;
                padding: 14px;
                border: 1px solid rgba(0,0,0,0.03);
                transition: transform 0.4s ease;
                flex-shrink: 0;
            }
            .adv-cat-card:hover .adv-cat-icon-wrap {
                transform: scale(1.1) rotate(8deg);
                box-shadow: none;
            }
            .adv-cat-icon-wrap img {
                width: 100%;
                height: 100%;
                object-fit: contain;
                transition: transform 0.4s ease;
            }
            .adv-cat-content {
                position: relative;
                z-index: 1;
                text-align: left;
                flex-grow: 1;
            }
            .adv-cat-title {
                font-size: 19px;
                font-weight: 800;
                color: #1e293b;
                margin: 0 0 4px 0;
            }
            .adv-cat-count {
                font-size: 14px;
                color: #64748b;
                font-weight: 600;
            }
        </style>
        @endpush

        <div class="row">
            <div class="col-md-12">
                <div class="slider-wrapper">
                    <div class="store-slider">
                        @foreach($popularCouponElements as $index => $item)
                        <div class="slick-slide-inner">
                            <a href="{{route('category.coupons', $item->id)}}" class="adv-cat-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                                <div class="adv-cat-icon-wrap">
                                    <img src="{{ getImage(getFilePath('category') . '/' . $item->image) }}" alt="Image">
                                </div>
                                <div class="adv-cat-content">
                                    <h4 class="adv-cat-title">
                                        @if (strlen(__($item->name)) > 20)
                                        {{ substr(__($item->name), 0, 20) . '...' }}
                                        @else
                                            {{ __($item->name) }}
                                        @endif
                                    </h4>
                                    <span class="adv-cat-count">{{$item->coupons->count()}} @lang('Coupons')</span>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        {{-- 
        <div class="row justify-content-center justify-content-sm-start cus-row mx-0 g-3">
            @foreach($popularCouponElements as $item)
            <div class="col-7 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="single-box transition position-relative d-center justify-content-start text-center gap-3 gap-md-4 p1-2nd-bg-color cus-border border b-eighth p-3 p-md-4 rounded-3  wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                    
                    <div class="abs-area position-absolute top-0 end-0 m-2 rounded-pill px-3 p1-2nd-bg-color cus-border border b-eighth">
                        <span class="f5-color fw-mid">#{{ $loop->iteration }}</span>
                    </div>
                    
                    <div class="d-center thumb-area rounded-circle">
                        <img class="rounded-circle img-fluid" src="{{ getImage(getFilePath('category') . '/' . $item->image) }}" alt="Image">
                    </div>
                    <div class="d-grid gap-2 text-start">
                        <h4 class="n15-color text-nowrap fw-bold">
                            <a href="{{route('category.coupons', $item->id)}}" class="title-cat">
                                @if (strlen(__($item->name)) > 20)
                                {{ substr(__($item->name), 0, 20) . '...' }}
                                @else
                                    {{ __($item->name) }}
                                @endif
                            </a>
                        </h4>
                        <span class="n15-color fs-eight">{{$item->coupons->count()}} @lang('coupons')</span>
                    </div>
                </div>
            </div>
            @endforeach
           
        </div>
         --}}
        <div class="row pt-2">
            <div class="col-lg-12 text-end">
                <a href="{{route('categories')}}" class="view-more-link">@lang('View More')</a>
            </div>
        </div>
    </div>
</section>