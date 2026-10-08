@php
    $popularCoupon = getContent('popular_coupon.content', true);
    $popularCouponsData = App\Models\Coupon::with(['category', 'store', 'wishlists'])
        ->where('status', 1)
        ->where('is_popular', 1)
        ->latest()
        ->limit(16)
        ->get();
@endphp

<section class="exclusive-section py-20 bg-light">
    <div class="container-fluid container-custom">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            {{ __($popularCoupon->data_values->heading) }}
                        </h2>
                        {{-- 
                        <a href="{{ route('popular.coupons') }}" class="view-more-link">@lang('View More')</a> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="slider-wrapper">
                    <div class="popular-slider">
                        @php
                            $ckTheme = [
                                ['bg' => 'linear-gradient(135deg, #ff9900, #ff5500)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
                                ['bg' => 'linear-gradient(135deg, #2874f0, #0053c8)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
                                ['bg' => 'linear-gradient(135deg, #c74c10, #802802)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
                                ['bg' => 'linear-gradient(135deg, #00c6ff, #0072ff)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
                                ['bg' => 'linear-gradient(135deg, #f093fb, #f5576c)', 'btn' => '#0053c8', 'badge' => '#ffd700']
                            ];
                        @endphp
                        @foreach ($popularCouponsData as $index => $item)
                        <div class="slick-slide-inner">
                            <div class="vertical-coupon-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                                <div class="vertical-card-img">
                                    @if($item->image)
                                        <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" alt="@lang('Coupon Image')">
                                    @elseif($item->store && $item->store->image)
                                        <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" alt="">
                                    @endif
                                </div>
                                
                                <a href="javascript:void(0)" class="vertical-grab-btn getCoupon" 
                                    data-id="{{$item->id}}" data-title="{{$item->title}}" 
                                    data-code="{{$item->code}}" data-description="{{$item->description}}" 
                                    data-link="{{$item->link}}">
                                    Grab Deal
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-lg-12 text-end">
                <a href="{{ route('popular.coupons') }}" class="view-more-link">@lang('View More')</a>
            </div>
        </div>
    </div>
</section>
