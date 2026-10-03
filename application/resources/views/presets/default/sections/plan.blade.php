@php
    $planContent = getContent('plan.content', true);
    $plans = App\Models\Plan::where('status',1)->orderBy('created_at','desc')->take(3)->get();
@endphp
<!-- < plan -->
<section class="plan-section section-bg-before py-20">
    <div class="container-fluid container-custom">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            {{ __($planContent->data_values->heading) }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gy-4 justify-content-center position-relative">
            @foreach($plans as $item)
            <div class="col-lg-4 col-md-6">
                <div class="pricing-plan-item {{ $item->where('status',1)->count() > 2 ? 'big-plan' : '' }}">

                    <div class="price-shape-2"></div>
                    <div class="pricing-plan-item__top">
                        <h3 class="title mb-2">{{__($item->name)}}</h3>
                        <h3 class="title price">{{__($general->cur_sym)}} {{showAmount($item->price)}}</h3>
                    </div>
                    
                    <div class="pricing-plan-item__list">
                        <h3 class="title count d-flex align-items-center">
                            <i class="fas fa-percentage"></i> @lang('Coupon') {{__($item->coupon_count)}}
                        </h3>
                        <ul>
                            @if(@$item->content)
                            @foreach(json_decode(@$item->content) as $value)
                            <li> <i class="fas fa-check-circle"></i>{{$value}}</li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                    <div class="pricing-plan-item__bottom">
                        <a class="btn btn--base" href="{{route('user.plan.payment',$item->id)}}">
                           @lang('Get Started') <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>
<!--  exclusive /> -->
