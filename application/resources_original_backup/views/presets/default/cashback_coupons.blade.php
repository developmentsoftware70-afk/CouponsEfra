@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
     $secondAd = App\Models\Ad::skip(1)->first();
@endphp
<!-- exclusive item section -->
<!-- < exclusive -->
<section class="exclusive-section py-20">
    <div class="container-fluid container-custom">
        <div class="row gy-4 justify-content-center">
            <div class="col-lg-3">
                <div class="side-bar-wrap">
                    <div class="section-search-box mb-4">
                        <form>
                            <input class="form--control" id="searchValue" name="search" type="text" placeholder="@lang('Search')">
                            <button><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>
                    <div class="category-box">
                        <div class="side-bar-wrap">
                            <div class="category-box">
                                <div class="categories">
                                    <h6 class="title">@lang('Categories')</h6>
                                    <div class="category-list">
                                        @foreach ($categories as $item)
                                        <div class="check-item">
                                            <div class="form--check categories-search mb-2">
                                                <input class="form-check-input filter-by-category" name="categories_{{$loop->iteration}}" type="checkbox" value="{{ $item->id }}" id="categories_{{$loop->iteration}}">
                                                <label for="categories_{{$loop->iteration}}" class="form-check-label">{{ $item->name }}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                        <button class="show-more-button btn btn--base btn--sm mt-2">@lang('Show More')</button>
                                    </div>
                                </div>
                                <div class="categories">
                                    <h6 class="title">@lang('Stores')</h6>
                                    <div class="category-list">
                                        @foreach ($stores as $item)
                                        <div class="check-item-stores">
                                            <div class="form--check categories-search mb-2">
                                                <input class="form-check-input filter-by-stores" id="stores_{{$loop->iteration}}" name="stores_{{$loop->iteration}}"
                                                    type="checkbox" value="{{ $item->id }}">
                                                <label for="stores_{{$loop->iteration}}" class="form-check-label">{{ $item->name }}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                        <button class="show-more-button-stores btn btn--base btn--sm mt-2">@lang('Show More')</button>
                                    </div>
                                </div>
                                @if($secondAd)
                                <div class="categories">
                                    <!-- ad image start -->
                                    <div class="sidebar-add-wrap position-relative">
                                       <div class="long-add-wrap--thumb">
                                            <a href="{{@$secondAd->link}}" target="_blank">
                                                <img src="{{ getImage(getFilePath('adImage') . '/' . @$secondAd->image) }}" alt="">
                                            </a>
                                       </div>
                                    </div>
                                     <!-- ad image end -->
                                </div>
                                @else
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
            <div class="col-lg-9 main-content">
                <div class="row gy-4">
                    @forelse($cashbackCoupons as $index => $item)
                    <div class="col-lg-4 col-md-6 col-sm-6">
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
                                        @if($item->is_cashback == 1)
                                        <p>{{ $item->cashback_amount }}@lang('% Cash Back')</p>
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
                                <a href="javascript:void(0)" class="btn btn--base w-100 getCoupon" data-id="{{$item->id}}" data-title="{{$item->title}}" data-code="{{$item->code}}" data-description="{{$item->description}}" data-link="{{$item->link}}">@lang('Get Code')</a>
                                 <p class="card-action"> {!! isExpired($item->id) !!}</p>
                            </div>
                        </div>
                        </a>
                    </div>
                    @empty
                    <p class="text-center">{{__($emptyMessage)}}</p>
                    @endforelse
                </div>
                <div class="row py-4">
                    <div class="col-lg-12 justify-content-center d-flex">
                        
                        @if ($cashbackCoupons->hasPages())
                        <div class="py-4">
                            {{ paginateLinks($cashbackCoupons) }}
                        </div>
                        @endif
                    </div>
                </div>
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