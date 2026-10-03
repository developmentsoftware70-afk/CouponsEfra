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
                                            $categoryCount = count($dealCategories);
                                        @endphp
                                        @foreach ($dealCategories as $item)
                                            <div class="check-item">
                                                <div class="form--check categories-search mb-2">
                                                    <input class="form-check-input filter-by-category" name="deal_categories_{{$loop->iteration}}" type="checkbox" value="{{ $item->id }}" id="deal_categories_{{$loop->iteration}}">
                                                    <label for="deal_categories_{{$loop->iteration}}" class="form-check-label">{{ $item->title }}</label>
                                                </div>
                                            </div>
                                            @if ($loop->iteration == 6 && $categoryCount > 6)
                                                <button class="show-more-button btn btn--base btn--sm mt-2">@lang('Show More')</button>
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
                    @forelse($deals as $index => $item)
                    <div class="col-lg-4 col-md-6 col-sm-6">
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