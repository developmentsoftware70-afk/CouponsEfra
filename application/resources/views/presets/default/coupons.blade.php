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
                                        @php
                                            $categoryCount = count($categories);
                                        @endphp
                                        @foreach ($categories as $item)
                                            <div class="check-item">
                                                <div class="form--check categories-search mb-2">
                                                    <input class="form-check-input filter-by-category" name="categories_{{$loop->iteration}}" type="checkbox" value="{{ $item->id }}" id="categories_{{$loop->iteration}}">
                                                    <label for="categories_{{$loop->iteration}}" class="form-check-label">{{ $item->name }}</label>
                                                </div>
                                            </div>
                                            @if ($loop->iteration == 6 && $categoryCount > 6)
                                                <button class="show-more-button btn btn--base btn--sm mt-2">@lang('Show More')</button>
                                            @endif
                                        @endforeach
                                    </div>
                                    
                                </div>
                                <div class="categories">
                                    <h6 class="title">@lang('Stores')</h6>
                                    <div class="category-list">
                                        @php
                                            $storeCount = count($stores);
                                        @endphp
                                        @foreach ($stores as $item)
                                        <div class="check-item-stores">
                                            <div class="form--check categories-search mb-2">
                                                <input class="form-check-input filter-by-stores" id="stores_{{$loop->iteration}}" name="stores_{{$loop->iteration}}"
                                                    type="checkbox" value="{{ $item->id }}">
                                                <label for="stores_{{$loop->iteration}}" class="form-check-label">{{ $item->name }}</label>
                                            </div>
                                        </div>
                                        @if($loop->iteration == 6 && $storeCount > 6) 
                                            <button class="show-more-button-stores btn btn--base btn--sm mt-2">@lang('Show More')</button>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                @if($firstAd)
                                    <div class="categories">
                                        <!-- ad image start -->
                                        <div class="sidebar-add-wrap position-relative">
                                        <div class="long-add-wrap--thumb">
                                                <a href="{{@$firstAd->link}}" target="_blank">
                                                    <img src="{{ getImage(getFilePath('adImage') . '/' .@$firstAd->image) }}" alt="">
                                                </a>
                                        </div>
                                        </div>
                                        <!-- ad image end -->
                                    </div>
                                @else

                                @endif
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
            <div class="col-lg-9  main-content">
                <div class="row gy-4">
                    @php
                        $ckTheme = [
                            ['bg' => 'linear-gradient(135deg, #ff9900, #ff5500)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
                            ['bg' => 'linear-gradient(135deg, #2874f0, #0053c8)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
                            ['bg' => 'linear-gradient(135deg, #c74c10, #802802)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
                            ['bg' => 'linear-gradient(135deg, #00c6ff, #0072ff)', 'btn' => '#0053c8', 'badge' => '#ffd700'],
                            ['bg' => 'linear-gradient(135deg, #f093fb, #f5576c)', 'btn' => '#0053c8', 'badge' => '#ffd700']
                        ];
                    @endphp
                    @forelse($coupons as $index => $item)
                    @php
                        $theme = $ckTheme[$index % count($ckTheme)];
                    @endphp
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <a href="javascript:void(0)" class="getCoupon ck-style-card-link" 
                            data-id="{{$item->id}}" data-title="{{$item->title}}" 
                            data-code="{{$item->code}}" data-description="{{$item->description}}" 
                            data-link="{{$item->link}}">
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
                                    <div class="ck-badge" style="background: {{ $theme['badge'] }}; color: #000;">
                                        @if($item->is_featured == 1)
                                            @lang('Featured')
                                        @elseif($item->is_exclusive == 1)
                                            @lang('Exclusive')
                                        @elseif($item->is_popular == 1)
                                            @lang('Popular')
                                        @elseif($item->is_sale == 1)
                                            @lang('Sale')
                                        @elseif($item->is_cashback == 1)
                                            @lang('Cash Back')
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
                    @empty
                    <p class="text-center">{{__($emptyMessage)}}</p>
                    @endforelse
                </div>
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
