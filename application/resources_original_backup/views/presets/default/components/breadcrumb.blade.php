@php
    $firstAd =App\Models\Ad::first();
@endphp
<!-- ==================== Breadcumb Start Here ==================== -->
<div class="breadcumb  section-bg-before-base py-50 bg--img" style="background: url({{asset($activeTemplateTrue.'images/bg/breadcrumb-bg.png')}})">
    <div class="container-fluid container-custom">
        {{-- 
        @if($firstAd)
        <div class="breadcrumb-long-add-wrap">
           <div class="long-add-wrap--thumb">
                <a href="{{@$firstAd->link}}" target="_blank">
                    <img src="{{ getImage(getFilePath('adImage') . '/' .@$firstAd->image) }}" alt="addImg">
                </a>
           </div>
        </div>
        @else
        @endif
         --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcumb__wrapper">
                    <h2 class="breadcumb__title"> {{__($pageTitle)}} </h2>
                    <ul class="breadcumb__list">
                        <li class="breadcumb__item"><a href="{{route('home')}}" class="breadcumb__link"><i class="las la-home"></i> @lang('Home')</a> </li>
                        <li class="breadcumb__item">/</li>
                        <li class="breadcumb__item"> <span class="breadcumb__item-text">  {{__($pageTitle)}} </span> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ==================== Breadcumb End Here ==================== -->