@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
    $secondAd = App\Models\Ad::skip(1)->first();
    $latestCoupon = getContent('latest_coupon.content', true);
@endphp
<!-- exclusive item section -->
<section class="exclusive-section py-20">
    <div class="container-fluid container-custom">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            {{ __($latestCoupon->data_values->heading) }}
                        </h2>
                       {{--  <a href="{{ route('cashback.coupons') }}">@lang('View More')</a> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4 main-content">
            @forelse($earningCoupons as $index => $item)
            <div class="col-lg-3 col-md-3 col-sm-6">
                <a href="javascript:void(0)" class="getCoupon" data-id="{{$item->id}}" data-title="{{$item->title}}" data-code="{{$item->code}}" data-description="{{$item->description}}" data-link="{{$item->link}}">
                    <div class="coupon-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                        <div class="card-thumb">
                            @if($item->image)
                            <img src="{{ getImage(getFilePath('coupon') . '/' . @$item->image) }}" class="img-fluid" alt="@lang('Coupon Image')">
                            @else
                            <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" class="img-fluid" alt="@lang('Store Image')">
                            @endif
                            <div class="card-ribbon-wrap">
                                <div class="ex-cta">
                                    @if($item->is_earning == 1)
                                    <p>@lang('Earning')</p>
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
                            <p class="card-title">{{__($item->title)}}</p>
                            <a href="javascript:void(0)" class="btn btn--base w-100 getCoupon" data-id="{{$item->id}}" data-title="{{$item->title}}" data-code="{{$item->code}}" data-description="{{$item->description}}" data-link="{{$item->link}}">{{ $item->code ? __('Get Code') : __('Get Deal') }}</a>
                                <p class="card-action"> {!! isExpired($item->id) !!}</p>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <p class="text-center">{{__($emptyMessage)}}</p>
            @endforelse
            <div class="col-lg-12 justify-content-center d-flex">
                @if ($earningCoupons->hasPages())
                <div class="py-4">
                    {{ paginateLinks($earningCoupons) }}
                </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            Deals
                        </h2>
                       {{--  <a href="{{ route('cashback.coupons') }}">@lang('View More')</a> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4 main-content">
            @forelse($earningDeals as $index => $item)
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="testimonial-card p-0 wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                    <a href="javascript:void(0)" class="getDeal" data-id="{{ $item->id }}" data-title="{{ $item->title }}" data-description="{{ $item->description }}" data-link="{{ $item->link }}"  data-button_label="{{ $item->button_label ?? __('Grab Deal') }}">
                        @if($item->image)
                        <img src="{{ getImage(getFilePath('deal') . '/' . @$item->image) }}" class="img-fluid" alt="">
                        @else
                        <img src="{{ getImage(getFilePath('dealCategory') . '/' . @$item->dealCategory->image) }}" class="img-fluid" alt="">
                        @endif
                    </a>
                </div>
            </div>
            @empty
            <p class="text-center">{{__($emptyMessage)}}</p>
            @endforelse
            <div class="col-lg-12 justify-content-center d-flex">
                @if ($earningDeals->hasPages())
                <div class="py-4">
                    {{ paginateLinks($earningDeals) }}
                </div>
                @endif
            </div>
        </div>
        
    </div>
</section>
<!--  exclusive /> -->
@endsection

@push('script')
<script>
    (function ($) {
        "use strict";
        
        // add to wishlist
        $(document).on('click', '.addToWishList', function() {
            'use strict';
            var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
            if (isLoggedIn) {
                var couponId = $(this).data('id');
                var button = $(this);
                $.ajax({
                    url: '{{ route('user.wishlist.add') }}',
                    type: 'get',
                    data: {
                        couponId: couponId,
                    },
                    success: function(response) {
                        if (response.hasOwnProperty('message')) {
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                            var heartIcon = button.find('i');
                            heartIcon.removeClass('far fa-heart').addClass('fas fa-heart');
                        } else {
                            Toast.fire({
                                icon: 'warning',
                                title: response.error
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = 'Error occurred while adding the Coupon to wishlist.';
                        Toast.fire({
                            icon: 'error',
                            title: errorMessage
                        });
                    }
                });
            } else {
                var errorMessage = 'Please log in to add items to your wishlist.';
                Toast.fire({
                    icon: 'warning',
                    title: errorMessage
                });
            }
        });
        // end wishlist

        // Filter coupon
        $("#searchValue").on('keyup', function () {
            var categories   = [];
            var searchValue = [];
            var stores   = [];
            
            var searchValue = $(this).val();
            getFilteredData(stores, categories,searchValue)
        });

        $("input[type='checkbox'][name^='categories_']").on('click', function(){
            var categories   = [];
            var stores   = [];
            var searchValue = [];

            $('.filter-by-category:checked').each(function() {
                if(!categories.includes(parseInt($(this).val()))){
                    categories.push(parseInt($(this).val()));
                }
            });
            getFilteredData(stores, categories,searchValue)
        });

        $("input[type='checkbox'][name^='stores_']").on('click', function(){
            var categories   = [];
            var stores   = [];
            var searchValue = [];
            $('.filter-by-stores:checked').each(function() {
                if(!stores.includes(parseInt($(this).val()))){
                    stores.push(parseInt($(this).val()));
                }
            });
            getFilteredData(stores, categories,searchValue)
        });

        function getFilteredData(stores, categories,searchValue){
            $.ajax({
                type: "get",
                url: "{{route('feature.coupon.filtered') }}",
                data:{
                
                    "categories": categories,
                    "stores": stores,
                    "search": searchValue

                },
                dataType: "json",
                success: function (response) {
                    if(response.html){
                        $('.main-content').html(response.html);
                    }

                    if(response.error){
                        notify('error', response.error);
                    }
                }
            });
        }
        // end filter product
    })(jQuery);
</script>
@endpush
