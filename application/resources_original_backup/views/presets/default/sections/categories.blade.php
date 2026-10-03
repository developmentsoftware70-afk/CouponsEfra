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
                        <a href="{{route('categories')}}">@lang('View More')</a> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="slider-wrapper">
            <div class="store-slider">
                @foreach($popularCouponElements as $index => $item)
                <div class="slick-slide-inner">
                    <div class="single-box h-100 transition position-relative d-center justify-content-start text-center gap-3 gap-md-4 p1-2nd-bg-color cus-border border b-eighth p-3 p-md-4 rounded-3  wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                        {{-- 
                        <div class="abs-area position-absolute top-0 end-0 m-2 rounded-pill px-3 p1-2nd-bg-color cus-border border b-eighth">
                            <span class="f5-color fw-mid">#{{ $loop->iteration }}</span>
                        </div>
                        --}}
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
                <a href="{{route('categories')}}">@lang('View More')</a>
            </div>
        </div>
    </div>
</section>