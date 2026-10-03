@php
    $featureStore = getContent('feature_store.content', true);
    //$featureStoreElements = App\Models\Store::active()->latest()->limit(8)->get();
    $featureStoreElements = App\Models\Store::with('coupons')->where('show_on_front', 1)->latest()->limit(10)->get();
@endphp
<section class="top-collections top-stores second py-20 bg-white">
    <div class="container-fluid container-custom">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            {{(__($featureStore->data_values->heading))}}
                        </h2>
                        {{-- 
                        <a href="{{route('feature.store')}}">@lang('View More')</a> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="slider-wrapper">
            <div class="store-slider">
                @foreach( $featureStoreElements as $index => $item)
                <div class="slick-slide-inner">
                    <a href="{{route('store.coupons', $item->id)}}" class="single-box transition h-100 d-center flex-column text-center gap-4 gap-md-6 p1-2nd-bg-color cus-border border b-fifth px-2 px-md-3 py-3 py-md-7 wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                        <div class="d-center thumb-area rounded-3">
                            <img class="rounded-3 img-fluid" src="{{ getImage(getFilePath('store') . '/' . $item->image) }}" alt="Image">
                        </div>
                        <div class="d-grid gap-2 text-center">
                            <span class="n15-color fw-semibold fs-eight">{{__($item->name)}}</span>
                            <h5 class="n15-color text-nowrap fw-bold">{{__($item->description)}}</h5>
                            <span class="n15-color fs-eight">{{ $item->coupons->count() }} @lang('coupons |')  {{ $item->coupons->where('status', 1)->count() }} @lang('Available')</span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        {{-- 
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 g-0">
            @foreach( $featureStoreElements as $item) 
            <div class="col">
                <a href="{{route('store.coupons', $item->id)}}" class="single-box transition d-center flex-column text-center gap-4 gap-md-6 p1-2nd-bg-color cus-border border b-fifth px-2 px-md-3 py-3 py-md-7 wow animate__animated animate__fadeInUp" data-wow-delay="0.4s">
                    <div class="d-center thumb-area rounded-3">
                        <img class="rounded-3 img-fluid" src="{{ getImage(getFilePath('store') . '/' . $item->image) }}" alt="Image">
                    </div>
                    <div class="d-grid gap-2 text-center">
                        <span class="n15-color fw-semibold fs-eight">{{__($item->name)}}</span>
                        <h5 class="n15-color text-nowrap fw-bold">{{__($item->description)}}</h5>
                        <span class="n15-color fs-eight">{{ $item->coupons->count() }} @lang('coupons |')  {{ $item->coupons->where('status', 1)->count() }} @lang('Available')</span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
         --}}
        <div class="row pt-2">
            <div class="col-lg-12 text-end">
                <a href="{{route('feature.store')}}">@lang('View More')</a>
            </div>
        </div>
    </div>
</section>