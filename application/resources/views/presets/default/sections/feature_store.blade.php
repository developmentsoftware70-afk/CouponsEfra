@php
    $featureStore = getContent('feature_store.content', true);
    //$featureStoreElements = App\Models\Store::active()->latest()->limit(8)->get();
    $featureStoreElements = App\Models\Store::with('coupons')->where('show_on_front', 1)->latest()->limit(10)->get();
@endphp
<section class="top-collections top-stores second py-20 bg-white">
    <div class="container-fluid container-custom">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            {{(__($featureStore->data_values->heading))}}
                        </h2>
                        {{-- 
                        <a href="{{route('feature.store')}}" class="view-more-link">@lang('View More')</a> --}}
                    </div>
                </div>
            </div>
        </div>
        <style>
            .tc-card {
                background: #ffffff;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.06);
                overflow: hidden;
                margin: 15px 10px; /* Maintains slider spacing */
                display: block;
                text-decoration: none !important;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .tc-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 15px 35px rgba(0,0,0,0.12);
            }
            .tc-top {
                height: 150px;
                padding: 20px;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
            }
            .tc-logo-wrapper {
                width: 60px;
                height: 60px;
                background: #ffffff;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 8px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            }
            .tc-logo-wrapper img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
            }
            .tc-badge-text {
                color: #ffffff;
                font-weight: 700;
                font-size: 14px;
                text-align: right;
                line-height: 1.2;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                text-shadow: 0 2px 4px rgba(0,0,0,0.15);
            }
            .tc-bottom {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 20px 20px 0 0;
                margin-top: -35px; /* Creates the overlap effect */
                padding: 25px 20px 20px;
                text-align: center;
                position: relative;
            }
            .tc-brand {
                font-size: 15px;
                font-weight: 800;
                color: #1e293b;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 5px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block;
            }
            .tc-title {
                font-size: 24px;
                font-weight: 900;
                color: #000000;
                margin-bottom: 15px;
                line-height: 1.1;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block;
            }
            .tc-dashed-box {
                border: 1px dashed #94a3b8;
                border-radius: 10px;
                padding: 8px 12px;
                font-size: 14px;
                font-weight: 700;
                color: #334155;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                background: #f8fafc;
                margin-bottom: 12px;
            }
            .tc-footer {
                font-size: 13px;
                font-weight: 600;
                color: #64748b;
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 6px;
            }
        </style>
        <div class="slider-wrapper">
            <div class="store-slider">
                @foreach( $featureStoreElements as $index => $item)
                @php
                    $gradients = [
                        'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                        'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
                        'linear-gradient(135deg, #ff0844 0%, #ffb199 100%)',
                        'linear-gradient(135deg, #f12711 0%, #f5af19 100%)'
                    ];
                    $bg = $gradients[$index % 4];
                @endphp
                <div class="slick-slide-inner">
                    <a href="{{route('store.coupons', $item->id)}}" class="tc-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                        <div class="tc-top" style="background: {{ $bg }};">
                            <div class="tc-logo-wrapper">
                                <img src="{{ getImage(getFilePath('store') . '/' . $item->image) }}" alt="Image">
                            </div>
                            <div class="tc-badge-text">GIFT<br>COUPON</div>
                        </div>
                        <div class="tc-bottom">
                            <div class="tc-brand">{{__($item->name)}}</div>
                            
                            @if($item->description)
                                <div class="tc-title">{{__($item->description)}}</div>
                            @else
                                <div style="height: 26px; margin-bottom: 15px;"></div>
                            @endif
                            
                            <div class="tc-dashed-box">
                                {{ $item->coupons->count() }} @lang('Coupons')
                            </div>
                            
                            <div class="tc-footer">
                                <i class="las la-check-circle"></i> {{ $item->coupons->where('status', 1)->count() }} @lang('Available')
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        {{-- 
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 g-0">
            @foreach( $featureStoreElements as $item) 
            <div class="col">
                <a href="{{route('store.coupons', $item->id)}}" class="single-box transition d-center flex-column text-center gap-4 gap-md-6 p1-2nd-bg-color cus-border border b-fifth px-2 px-md-3 py-3 py-md-7 wow animate__animated animate__fadeInUp" data-wow-delay="0.4s">
                    <div class="d-center thumb-area rounded-3">
                        <img class="rounded-3 img-fluid" src="{{ getImage(getFilePath('store') . '/' . $item->image) }}" alt="Image">
                    </div>
                    <div class="d-grid gap-2 text-center">
                        <span class="n15-color fw-semibold fs-eight">{{__($item->name)}}</span>
                        <h5 class="n15-color text-nowrap fw-bold">{{__($item->description)}}</h5>
                        <span class="n15-color fs-eight">{{ $item->coupons->count() }} @lang('coupons |')  {{ $item->coupons->where('status', 1)->count() }} @lang('Available')</span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
         --}}
        <div class="row pt-2">
            <div class="col-lg-12 text-end">
                <a href="{{route('feature.store')}}" class="view-more-link">@lang('View More')</a>
            </div>
        </div>
    </div>
</section>