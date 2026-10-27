@extends($activeTemplate . 'layouts.master')
@section('content')

@if (auth()->check() && auth()->user()->signup_type == 2) 
<div class="row gy-4 justify-content-center">
    <div class="col-lg-4 col-sm-6"> 
        <div class="room-card">
            <a href="{{route('user.store.index')}}"></a>
            <div class="room-card__icon card-base">
                <i class="fas fa-percentage"></i>
            </div>
            <div class="room-card__text">
                <h4 class="title">{{__($couponsCount)}}</h4> 
                <span>@lang('Total Coupon')</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6">
        <div class="room-card">
            <a href="{{route('user.store.active')}}"></a>
            <div class="room-card__icon card-success">
                <i class="fas fa-percentage"></i>
            </div>
            <div class="room-card__text">
                <h4 class="title">{{__($activeCoupon)}}</h4> 
                <span>@lang('Active Coupon')</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6">
        <div class="room-card">
            <a href="{{route('user.store.pending')}}"></a>
            <div class="room-card__icon card-danger">
                <i class="fas fa-percentage"></i>
            </div>
            <div class="room-card__text"> 
                <h4 class="title">{{__($pendingCoupon)}}</h4> 
                <span>@lang('Pending Coupon')</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6">
        <div class="room-card">
            <a href="{{route('user.store.index')}}"></a>
            <div class="room-card__icon card-success-1">
                <i class="fas fa-suitcase"></i>
            </div>
            <div class="room-card__text">
                <h4 class="title">{{__($storeCount)}}</h4> 
                <span>@lang('total Store')</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6">
        <div class="room-card">
            <a href="{{route('user.store.active')}}"></a>
            <div class="room-card__icon card-info">
                <i class="fas fa-suitcase"></i>
            </div>
            <div class="room-card__text">
                <h4 class="title">{{__($activeStore)}}</h4> 
                <span>@lang('Active Store')</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6">
        <div class="room-card">
            <a href="{{route('user.store.pending')}}"></a>
            <div class="room-card__icon card-danger">
                <i class="fas fa-suitcase"></i>
            </div>
            <div class="room-card__text">
                <h4 class="title">{{__($pendingStore)}}</h4> 
                <span>@lang('Pending Store')</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6"> 
        <div class="room-card">
            <div class="room-card__icon card-violet">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="room-card__text">
                <h4 class="title">{{$general->cur_sym}}{{ showAmount($totalEarningsAmount) }}</h4> 
                <span>@lang('Total Earning')</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6">
        <div class="room-card">
            <div class="room-card__icon card-warning">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="room-card__text">
                <h4 class="title">{{$general->cur_sym}}{{ showAmount($pendingPaymentAmount) }}</h4> 
                <span>@lang('Pending Payment')</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6">
        <div class="room-card">
            <div class="room-card__icon card-success-1">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="room-card__text">
                <h4 class="title">{{$general->cur_sym}}{{ showAmount($disbursedAmount) }}</h4> 
                <span>@lang('Disbursed Amount')</span>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="account-form">
            <div class="col-12">
                <h6 class="dash-title">@lang('Coupons List')</h6>
            </div>
            <div class="table-responsive--sm table-responsive">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('Title')</th>
                            <th>@lang('Image')</th>
                            <th>@lang('Category')</th>
                            <th>@lang('Code')</th>
                            <th>@lang('Featured')</th>
                            <th>@lang('Exclusive')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons as $item)
                        <tr>
                            <td data-label="@lang('Title')">{{__($item->title)}}</td>
                            <td data-label="@lang('Image')">
                                <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" alt="@lang('store-image')" style="width: 80px">
                            </td>
                            <td data-label="@lang('Category')">{{__(@$item->category->name)}}</td>
                            <td data-label="@lang('Code')">{{__($item->code)}}</td>
                            <td data-label="@lang('Featured')">
                                @if($item->is_featured == 1)
                                
                                <span class="base-color"><i class="fas fa-check-circle"></i></span>
                                @else 
                                
                                <span><i class="fas fa-times"></i></span>
                                @endif
                            </td>
                            <td data-label="@lang('Exclusive')">
                                @if($item->is_exclusive == 1)
                                
                                <span><i class="fas fa-check-circle"></i></span>
                                @else 
                                
                                <span><i class="fas fa-times"></i></span>
                                @endif
                            </td>
                            <td data-label="@lang('Status')">
                                @if($item->status == 1)
                                
                                <span class="badge badge--success">@lang('Active')</span>
                                @else 
                                
                                <span class="badge badge--danger">@lang('Inactive')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Actions')">
                                <div class="button--group">
                                    <a href="{{route("user.coupons.edit",$item->id)}}" title="@lang('Edit')"
                                        class="btn btn--base btn--sm">
                                        <i class="la la-edit"></i>
                                    </a>                                     
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td data-label="{{ __($emptyMessage) }} class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table><!-- table end -->
                @if ($coupons->hasPages())
                    <div class="d-flex justify-content-end py-4">
                        {{ paginateLinks($coupons) }}
                    </div>
                @endif
            </div>
        </div><!-- card end -->
    </div>
</div>
@else
<div class="row gy-4 justify-content-center">
    <div class="col-lg-4 col-sm-6"> 
        <div class="room-card">
            <div class="room-card__icon card-violet">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="room-card__text">
                <h4 class="title">{{$general->cur_sym}}{{ showAmount($totalEarningsAmount) }}</h4> 
                <span>@lang('Total Earning')</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6">
        <div class="room-card">
            <div class="room-card__icon card-warning">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="room-card__text">
                <h4 class="title">{{$general->cur_sym}}{{ showAmount($pendingPaymentAmount) }}</h4> 
                <span>@lang('Pending Payment')</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6">
        <div class="room-card">
            <div class="room-card__icon card-success-1">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="room-card__text">
                <h4 class="title">{{$general->cur_sym}}{{ showAmount($disbursedAmount) }}</h4> 
                <span>@lang('Disbursed Amount')</span>
            </div>
        </div>
    </div>
</div>
@endif

<section class="dashboard-section account-form mt-4">
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center mb-2">
            <h6 class="dash-title mb-0"><i class="fas fa-shopping-bag me-1 text--base"></i> @lang('Recent Orders & Tracking')</h6>
            <div class="d-flex align-items-center gap-2">
                <span class="badge badge--success live-indicator">
                    <span class="live-dot"></span> @lang('Live Tracking')
                </span>
                <a href="{{ route('user.orders') }}" class="btn btn--base btn--sm py-1 px-3">@lang('View All')</a>
            </div>
        </div>
        <div class="col-lg-12">
            <table class="table table--responsive--lg">
                <thead>
                    <tr>
                        <th>@lang('Order ID')</th>
                        <th>@lang('Product / Store')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Source')</th>
                        <th>@lang('Time')</th>
                        <th>@lang('Status')</th>
                    </tr>
                </thead>
                <tbody id="dashboard-orders-body">
                    @forelse ($orders as $key => $order)
                        <tr id="dash-order-{{ $order->id }}">
                            <td data-label="@lang('Order ID')">
                                <span class="fw-bold text--base">#{{ $order->id }}</span>
                            </td>
                            <td data-label="@lang('Product / Store')">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 40px; height: 40px; min-width: 40px; border-radius: 6px; border: 1px solid #e2e8f0; background: #fff; display: flex; align-items: center; justify-content: center; padding: 2px;">
                                        @if($order->image_url)
                                            <img src="{{ $order->image_url }}" alt="{{ $order->product_name }}" style="width: 100%; height: 100%; object-fit: contain;">
                                        @else
                                            <i class="fas fa-store text-muted fs-14"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="d-block fw-semibold text-dark fs-13" title="{{ $order->product_name }}">
                                            {{ Str::limit($order->product_name, 45) }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td data-label="@lang('Amount')">
                                @if($order->amount > 0)
                                    <strong class="d-block">{{ showAmount($order->amount) }} {{ $order->currency ?? 'INR' }}</strong>
                                    @if($order->payout_amount > 0)
                                        <small class="text--success fw-semibold"><i class="fas fa-coins me-1"></i>+{{ showAmount($order->payout_amount) }}</small>
                                    @endif
                                @else
                                    <span class="badge badge--warning">@lang('Pending Verify')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Source')">
                                <span class="badge badge--secondary text-capitalize">{{ str_replace('_', ' ', $order->source ?? 'affiliate') }}</span>
                            </td>
                            <td data-label="@lang('Time')">{{ showDateTime($order->created_at, 'd M, Y h:i A') }}</td>
                            <td data-label="@lang('Status')">
                                @php
                                    $st = strtolower($order->status);
                                    $badge = match($st) {
                                        'confirmed', 'delivered' => 'badge--success',
                                        'cancelled', 'failed'    => 'badge--danger',
                                        'unreviewed'             => 'badge--primary',
                                        default                  => 'badge--warning',
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td data-label="@lang('Orders')" class="text-muted text-center py-3" colspan="100%">
                                @lang('No orders tracked yet. Click on any deal or coupon to track cashback!')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>


<section class="dashboard-section account-form mt-4">
    <div class="row">
        <div class="col-12">
            <h6 class="dash-title">@lang('WishList Table')</h6>
        </div>
        <div class="col-lg-12">
            <table class="table table--responsive--lg">
                <thead>
                    <tr>
                        <th>@lang('SL')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Time')</th>
                        <th>@lang('Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($wishLists as $key =>$item)
                        <tr>
                            <td data-label="@lang('SL')">{{ __($key + 1) }}</td>
                            <td data-label="@lang('Name')"><a href="{{ route('coupon') }}">{{ __($item->coupon->title) }}</a></td>
                            <td data-label="@lang('Time')">{{ showDateTime($item->created_at) }}</td>

                            <td data-label="@lang('Actions')">
                                <div class="button--group">
                                    <a href="{{ route('user.wishlist.remove', $item->id) }}"
                                        title="@lang('Delete')" class="btn btn--base btn--sm">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td data-label="@lang('Wishlists')" class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table><!-- table end -->
            @if ($wishLists->hasPages())
                <div class="d-flex justify-content-end py-4">
                    {{ paginateLinks($wishLists) }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@push('style')
<style>
.live-indicator {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    font-size: 11px;
    font-weight: 600;
}
.live-dot {
    width: 7px;
    height: 7px;
    background-color: #fff;
    border-radius: 50%;
    animation: livePulse 1.5s infinite ease-in-out;
}
@keyframes livePulse {
    0% { transform: scale(0.9); opacity: 0.7; }
    50% { transform: scale(1.3); opacity: 1; }
    100% { transform: scale(0.9); opacity: 0.7; }
}
</style>
@endpush

@push('script')
<script>
    (function () {
        "use strict";
        // Auto sync orders in real time every 10 seconds
        setInterval(function() {
            fetch("{{ route('user.home') }}", {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTbody = doc.getElementById('dashboard-orders-body');
                const currentTbody = document.getElementById('dashboard-orders-body');
                if (newTbody && currentTbody && newTbody.innerHTML.trim() !== currentTbody.innerHTML.trim()) {
                    currentTbody.innerHTML = newTbody.innerHTML;
                }
            })
            .catch(() => {});
        }, 10000);
    })();
</script>
@endpush
