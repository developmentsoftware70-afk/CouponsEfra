<div class="row gy-4">
    @forelse($coupons as $index => $item)
    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
        <div class="vertical-coupon-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.1 + ($index * 0.05) }}s">
            <div class="vertical-card-img">
                @if($item->image)
                    <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" alt="{{ $item->title ?? 'Deal' }}">
                @elseif($item->store && $item->store->image)
                    <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" alt="{{ $item->store->name ?? 'Store' }}">
                @endif
            </div>
            
            <a href="{{ route('coupon.details', $item->slug ?? $item->id) }}" class="vertical-grab-btn getCoupon" 
                data-id="{{$item->id}}" data-slug="{{$item->slug}}">
                @lang('Grab Deal')
            </a>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 bg-white rounded-3 border">
            <div class="mb-3">
                <i class="las la-search-minus" style="font-size: 48px; color: #94a3b8;"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">@lang('No deals or coupons found')</h5>
            <p class="text-muted small mb-0">@lang('Try clearing or adjusting your filters to see more results.')</p>
        </div>
    </div>
    @endforelse
</div>
