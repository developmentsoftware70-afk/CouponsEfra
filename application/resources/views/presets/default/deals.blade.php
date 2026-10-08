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
@include($activeTemplate.'components.sidebar', ['sidebarCategories' => $dealCategories ?? [], 'categoryPrefix' => 'deal_categories_'])
               
            </div>
            <div class="col-lg-9  main-content">
                <div class="row gy-4">
                    <style>
                    .vertical-coupon-card {
                        position: relative;
                        border-radius: 12px;
                        overflow: hidden;
                        margin-bottom: 24px;
                        height: 220px;
                        display: block;
                        background: #f8f9fa;
                        border: 1px solid #eaeaea;
                    }
                    .vertical-card-img {
                        width: 100%;
                        height: 100%;
                    }
                    .vertical-card-img img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                    }
                    .vertical-grab-btn {
                        position: absolute;
                        bottom: 12px;
                        right: 12px;
                        background: #0053c8;
                        color: #fff;
                        padding: 8px 16px;
                        border-radius: 6px;
                        font-weight: 600;
                        text-decoration: none;
                        font-size: 14px;
                        z-index: 2;
                        transition: background 0.2s;
                    }
                    .vertical-grab-btn:hover {
                        background: #003a8c;
                        color: #fff;
                    }
                    </style>
                    @forelse($deals as $index => $item)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="vertical-coupon-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                            <div class="vertical-card-img">
                                @if($item->image)
                                    <img src="{{ getImage(getFilePath('deal') . '/' . @$item->image) }}" alt="">
                                @else
                                    <img src="{{ getImage(getFilePath('dealCategory') . '/' . @$item->dealCategory->image) }}" alt="">
                                @endif
                            </div>
                            
                            <a href="javascript:void(0)" class="vertical-grab-btn getDeal" 
                                data-id="{{ $item->id }}" data-title="{{ $item->title }}" 
                                data-description="{{ $item->description }}" data-link="{{ $item->link }}"  
                                data-button_label="{{ $item->button_label ?? __('Grab Deal') }}">
                                {{ $item->button_label ?? __('Grab Deal') }}
                            </a>
                        </div>
                    </div>
                    @empty
                    <p class="text-center">{{__($emptyMessage)}}</p>
                    @endforelse
                </div>
            </div>
        
        </div>
        <div class="row py-4">
            @if ($deals->hasPages())
                <div class="py-4">
                    {{ paginateLinks($deals) }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            // Filter deal
            $("#searchValue").on('keyup', function () {
                var dealCategories   = [];
                var searchValue = [];
            
                var searchValue = $(this).val();
                getFilteredData(dealCategories,searchValue)
            });

            $("input[type='checkbox'][name^='deal_categories_']").on('click', function(){
                var dealCategories   = [];
                var searchValue = [];
                $('.filter-by-category:checked').each(function() {
                    if(!dealCategories.includes(parseInt($(this).val()))){
                        dealCategories.push(parseInt($(this).val()));
                    }
                });
                getFilteredData(dealCategories,searchValue)
            });

            function getFilteredData(dealCategories,searchValue){

                $.ajax({
                    type: "get",
                    url: "{{ route('deal.filtered') }}",
                    data:{
                    
                        "dealCategories": dealCategories,
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