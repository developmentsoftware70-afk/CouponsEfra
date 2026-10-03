@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="top-collections top-stores second py-20 bg-white">
    <div class="container-fluid container-custom">
        <div class="row justify-content-center justify-content-sm-start cus-row d-flex d-xxl-grid cus-grid grid-five g-0 g-lg-0">
            @foreach( $featureStore as $index => $item) 
            <div class="col-7 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <a href="{{route('store.coupons', $item->id)}}" class="single-box transition h-100 d-center flex-column text-center gap-4 gap-md-6 p1-2nd-bg-color cus-border border b-fifth px-2 px-md-3 py-3 py-md-7 wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                    <div class="d-center thumb-area rounded-3">
                        <img class="rounded-3 img-fluid" src="{{ getImage(getFilePath('store') . '/' . $item->image) }}" alt="{{__($item->name)}}">
                    </div>
                    <div class="d-grid text-center">
                        <span class="n15-color fw-semibold fs-eight">{{__($item->name)}}</span>
                        <h5 class="n15-color text-nowrap fw-bold">{{__($item->description)}}</h5>
                        <span class="n15-color fs-eight">{{ $item->coupons->count() }} @lang('coupons |')  {{ $item->coupons->where('status', 1)->count() }} @lang('Available')</span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        <div class="row py-4">
            @if ($featureStore->hasPages())
                <div class="py-4">
                    {{ paginateLinks($featureStore) }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
