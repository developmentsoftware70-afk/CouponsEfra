@php
    $featureCoupon = getContent('feature_coupon.content', true);
    $featuredCouponsData = App\Models\Coupon::with(['category', 'store', 'wishlists'])
        ->where('status', 1)
        ->where('is_featured', 1)
        ->latest()
        ->limit(16)
        ->get();
@endphp

<section class="exclusive-section py-20 bg-white">
    <div class="container-fluid container-custom">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            {{ __($featureCoupon->data_values->heading) }}
                        </h2>
                        {{-- 
                        <a href="{{ route('feature.coupons') }}">@lang('View More')</a> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="slider-wrapper">
                    <div class="popular-slider">
                        @foreach ($featuredCouponsData as $index => $item)
                        <div class="slick-slide-inner">
                            <a href="javascript:void(0)" class="getCoupon"
                                data-id="{{ $item->id }}" data-title="{{ $item->title }}"
                                data-code="{{ $item->code }}" data-description="{{ $item->description }}"
                                data-link="{{ $item->link }}">
                                <div class="coupon-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                                <div class="card-thumb">
                                    @if($item->image)
                                    <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" class="img-fluid" alt="@lang('Coupon Image')">
                                    @else
                                    <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" class="img-fluid" alt="">
                                    @endif
                                    <div class="card-ribbon-wrap">
                                        <div class="ex-cta">
                                            @if($item->is_featured == 1)
                                            <p>@lang('Featured')</p>
                                            @else 
                                            <p>@lang('New')</p>
                                            @endif
                                        </div>
                                        <button class="fav-cta addToWishList" data-id="{{ $item->id }}">
                                            @if (auth()->check() && $item->wishlists->count() > 0)
                                                <i class="fas fa-heart"></i>
                                            @else
                                                <i class="far fa-heart"></i>
                                            @endif
                                        </button>
                                    </div>
                                </div>
                                <div class="card-content-wrap">
                                    <p class="card-title">{{ __($item->title) }}</p>
                                    <a href="javascript:void(0)" class="btn btn--base w-100 getCoupon"
                                    data-id="{{ $item->id }}" data-title="{{ $item->title }}"
                                    data-code="{{ $item->code }}" data-description="{{ $item->description }}"
                                    data-link="{{ $item->link }}">@lang('Get Code')</a>
                                    <p class="card-action">{!! isExpired($item->id) !!}</p>
                                    
                                </div>
                            </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row pt-2">
            <div class="col-lg-12 text-end">
                <a href="{{ route('feature.coupons') }}">@lang('View More')</a>
            </div>
        </div>
    </div>
</section>
