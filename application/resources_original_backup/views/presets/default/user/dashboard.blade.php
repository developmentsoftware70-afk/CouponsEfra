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
