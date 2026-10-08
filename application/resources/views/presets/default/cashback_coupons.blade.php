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
@include($activeTemplate.'components.sidebar', ['sidebarCategories' => $categories ?? [], 'categoryPrefix' => 'categories_', 'sidebarStores' => $stores ?? [], 'storePrefix' => 'stores_'])
               
            </div>
            <div class="col-lg-9 main-content">
                <div class="row gy-4">

                    @forelse($cashbackCoupons as $index => $item)
                    <div class="col-lg-4 col-md-6 col-sm-12">
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
                                {{ $item->code ? __('Get Code') : __('Get Deal') }}
                            </a>
                        </div>
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
