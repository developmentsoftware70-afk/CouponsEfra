<div class="row gy-4">
    @forelse($featureCoupons as $index => $item)
    <div class="col-12 col-md-6 col-lg-4">
        <div class="vertical-coupon-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
            <div class="vertical-card-img">
                @if($item->image)
                    <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" alt="@lang('Coupon Image')">
                @elseif($item->store && $item->store->image)
                    <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" alt="">
                @endif
            </div>
            
            <a href="{{ route('coupon.details', $item->slug ?? $item->id) }}" class="vertical-grab-btn getCoupon" 
                data-id="{{$item->id}}" data-slug="{{$item->slug}}">
                @lang('Grab Deal')
            </a>
        </div>
    </div>
    @empty
    <p class="text-center">{{__($emptyMessage ?? 'No coupons found')}}</p>
    @endforelse
</div>
