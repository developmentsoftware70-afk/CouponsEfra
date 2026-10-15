@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
    $firstAd =App\Models\Ad::first();
    $secondAd = App\Models\Ad::skip(1)->first();
@endphp

<section class="exclusive-section py-20">
    <div class="container-fluid container-custom">
        <div class="row gy-4 justify-content-center">
            <div class="col-lg-3">
@include($activeTemplate.'components.sidebar', ['sidebarCategories' => $categories ?? [], 'categoryPrefix' => 'categories_', 'sidebarStores' => $stores ?? [], 'storePrefix' => 'stores_'])
               
            </div>
            <div class="col-lg-9  main-content">
                <div class="row gy-4">

                    @forelse($coupons as $index => $item)
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
                                Grab Deal
                            </a>
                        </div>
                    </div>
                    @empty
                    <p class="text-center">{{__($emptyMessage)}}</p>
                    @endforelse
                </div>

                @if(isset($relatedCategoryCoupons) && $relatedCategoryCoupons->count() > 0)
                <div class="mt-5 pt-4 border-top">
                    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1" style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">
                                <i class="las la-fire text-danger me-1"></i>
                                @lang('Similar') {{ @$primaryCategory->name }} @lang('Offers from Other Brands')
                            </h4>
                            <p class="text-muted mb-0" style="font-size: 0.875rem;">
                                @lang('Explore more verified deals in') {{ @$primaryCategory->name }}
                            </p>
                        </div>
                        @if(isset($primaryCategory))
                        <a href="{{ route('category.coupons', $primaryCategory->id) }}" class="btn btn--base btn--sm">
                            @lang('View All') {{ $primaryCategory->name }} &rarr;
                        </a>
                        @endif
                    </div>
                    <div class="row gy-4">
                        @foreach($relatedCategoryCoupons as $relIndex => $relItem)
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="vertical-coupon-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($relIndex * 0.1) }}s">
                                <div class="vertical-card-img position-relative">
                                    @if($relItem->image)
                                        <img src="{{ getImage(getFilePath('coupon') . '/' . @$relItem->image) }}" alt="@lang('Coupon Image')">
                                    @elseif($relItem->store && $relItem->store->image)
                                        <img src="{{ getImage(getFilePath('store') . '/' . @$relItem->store->image) }}" alt="">
                                    @endif
                                </div>
                                <div class="px-3 pt-2 pb-1 text-center">
                                    <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 11px; font-weight: 600;">
                                        {{ $relItem->store->name ?? 'Brand' }}
                                    </span>
                                    <h6 class="mt-2 mb-1" style="font-size: 14px; font-weight: 600; line-height: 1.3;">
                                        {{ Str::limit($relItem->title, 35) }}
                                    </h6>
                                </div>
                                <a href="javascript:void(0)" class="vertical-grab-btn getCoupon" 
                                    data-id="{{$relItem->id}}" data-title="{{$relItem->title}}" 
                                    data-code="{{$relItem->code}}" data-description="{{$relItem->description}}" 
                                    data-link="{{$relItem->link}}">
                                    Grab Deal
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        
        </div>
        <div class="row py-4">
            @if ($coupons->hasPages())
                <div class="py-4">
                    {{ paginateLinks($coupons) }}
                </div>
            @endif
        </div>
    </div>
</section>
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
                url: "{{ route('coupon.filtered') }}",
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
