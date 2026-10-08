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
                                                                        @php
                            $theme = $ckTheme[4];
                        @endphp
                        <div class="slick-slide-inner">
                            <a href="javascript:void(0)" class="getCoupon ck-style-card-link"
                                data-id="{{ $item->id }}" data-title="{{ $item->title }}"
                                data-code="{{ $item->code }}" data-description="{{ $item->description }}"
                                data-link="{{ $item->link }}">
                                <div class="ck-style-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s" style="background: {{ $theme['bg'] }};">
                                    <div class="ck-card-left">
                                        <h3 class="ck-title">{{ __($item->title) }}</h3>
                                        <p class="ck-subtitle">{{ Str::limit(html_entity_decode(strip_tags($item->description)), 40) }}</p>
                                        <div class="ck-btn-wrap">
                                            <span class="ck-btn">
                                                <span class="ck-btn-icon">CK</span> 
                                                {{ $item->code ? __('Get Code') : __('Get Deal') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ck-card-right">
                                        <button class="addToWishList" data-id="{{ $item->id }}" style="position:absolute; top:20px; left:0; background:rgba(255,255,255,0.2); color:#fff; border:none; border-radius:50%; width:30px; height:30px; z-index:5;">
                                            @if (auth()->check() && $item->wishlists->count() > 0)
                                                <i class="fas fa-heart"></i>
                                            @else
                                                <i class="far fa-heart"></i>
                                            @endif
                                        </button>
                                        <div class="ck-badge" style="background: {{ $theme['badge'] }}; color: #000;">
                                            @if($item->is_cashback == 1)
                                                {{ $item->cashback_amount }}@lang('% Cash Back')
                                            @else 
                                                @lang('New')
                                            @endif
                                        </div>
                                        <div class="ck-image-wrap">
                                            @if($item->image)
                                            <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" alt="@lang('Coupon Image')">
                                            @elseif($item->store && $item->store->image)
                                            <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" alt="">
                                            @endif
                                        </div>
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
                <a href="{{ route('popular.coupons') }}" class="view-more-link">@lang('View More')</a>
            </div>
        </div>
    </div>
</section>



