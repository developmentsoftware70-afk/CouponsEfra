@php
    $featureStore = getContent('feature_store.content', true);
    $featureStoreElements = App\Models\Store::with('coupons')->where('show_on_front', 1)->latest()->limit(10)->get();
    $cardBg = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
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
                    </div>
                </div>
            </div>
        </div>
        <style>
            .tc-card {
                background: #ffffff;
                border-radius: 20px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 4px 18px -2px rgba(15, 23, 42, 0.06), 0 2px 6px -1px rgba(15, 23, 42, 0.03);
                overflow: hidden;
                margin: 15px 10px;
                display: flex;
                flex-direction: column;
                text-decoration: none !important;
                transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
                position: relative;
            }
            .tc-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 32px -8px rgba(99, 102, 241, 0.22), 0 8px 16px -4px rgba(15, 23, 42, 0.08);
                border-color: rgba(99, 102, 241, 0.3);
            }
            .tc-top {
                height: 125px;
                padding: 16px 18px;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                position: relative;
                overflow: hidden;
            }
            .tc-top-glow {
                position: absolute;
                width: 130px;
                height: 130px;
                background: radial-gradient(circle, rgba(255,255,255,0.28) 0%, rgba(255,255,255,0) 70%);
                top: -40px;
                right: -30px;
                border-radius: 50%;
                pointer-events: none;
            }
            .tc-logo-wrapper {
                width: 58px;
                height: 58px;
                background: #ffffff;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 6px;
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.14);
                border: 2px solid rgba(255, 255, 255, 0.85);
                position: relative;
                z-index: 2;
                transition: transform 0.3s ease;
            }
            .tc-card:hover .tc-logo-wrapper {
                transform: scale(1.08) rotate(-2deg);
            }
            .tc-logo-wrapper img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
            }
            .tc-badge-pill {
                background: rgba(255, 255, 255, 0.22);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.45);
                border-radius: 20px;
                padding: 4px 10px;
                font-size: 11px;
                font-weight: 800;
                letter-spacing: 0.6px;
                color: #ffffff;
                text-transform: uppercase;
                display: inline-flex;
                align-items: center;
                gap: 5px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                position: relative;
                z-index: 2;
            }
            .tc-badge-pill i {
                font-size: 13px;
            }
            .tc-bottom {
                background: #ffffff;
                border-radius: 20px 20px 0 0;
                margin-top: -24px;
                padding: 20px 18px 16px;
                text-align: center;
                position: relative;
                z-index: 3;
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            .tc-brand {
                font-size: 15px;
                font-weight: 800;
                color: #0f172a;
                text-transform: uppercase;
                letter-spacing: 0.4px;
                margin-bottom: 4px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block;
            }
            .tc-desc {
                font-size: 13px;
                font-weight: 600;
                color: #64748b;
                line-height: 1.35;
                height: 36px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                margin-bottom: 12px;
            }
            .tc-desc i {
                color: #f59e0b;
                margin-right: 3px;
            }
            .tc-coupon-box {
                background: #f8fafc;
                border: 1.5px dashed #cbd5e1;
                border-radius: 12px;
                padding: 8px 14px;
                font-size: 13px;
                font-weight: 700;
                color: #1e293b;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 7px;
                margin: 0 auto 14px;
                width: 100%;
                transition: all 0.3s ease;
            }
            .tc-coupon-box i {
                color: #6366f1;
                font-size: 16px;
            }
            .tc-card:hover .tc-coupon-box {
                background: #eef2ff;
                border-color: #818cf8;
                color: #4338ca;
            }
            .tc-card:hover .tc-coupon-box i {
                color: #4f46e5;
            }
            .tc-footer-action {
                border-top: 1px solid #f1f5f9;
                padding-top: 10px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-size: 12px;
                font-weight: 600;
            }
            .tc-avail {
                color: #10b981;
                display: flex;
                align-items: center;
                gap: 4px;
            }
            .tc-avail i {
                font-size: 14px;
            }
            .tc-explore-btn {
                color: #6366f1;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 4px;
                transition: transform 0.2s ease, color 0.2s ease;
            }
            .tc-card:hover .tc-explore-btn {
                color: #4338ca;
                transform: translateX(3px);
            }
            
            /* Responsive styling for tc-card on mobile */
            @media (max-width: 767px) {
                .tc-top {
                    height: 95px;
                    padding: 10px 12px;
                }
                .tc-logo-wrapper {
                    width: 44px;
                    height: 44px;
                    border-radius: 10px;
                    padding: 4px;
                }
                .tc-badge-pill {
                    font-size: 9.5px;
                    padding: 3px 7px;
                }
                .tc-bottom {
                    margin-top: -18px;
                    padding: 14px 10px 10px;
                }
                .tc-brand {
                    font-size: 13px;
                }
                .tc-desc {
                    font-size: 11px;
                    height: 30px;
                    margin-bottom: 8px;
                }
                .tc-coupon-box {
                    font-size: 11.5px;
                    padding: 6px 10px;
                    margin-bottom: 10px;
                }
                .tc-footer-action {
                    font-size: 11px;
                    padding-top: 8px;
                }
                .tc-card {
                    margin: 8px 4px;
                }
            }
        </style>
        <div class="slider-wrapper">
            <div class="store-slider">
                @foreach( $featureStoreElements as $index => $item)
                <div class="slick-slide-inner">
                    <a href="{{route('store.coupons', $item->id)}}" class="tc-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                        <div class="tc-top" style="background: {{ $cardBg }};">
                            <div class="tc-top-glow"></div>
                            <div class="tc-logo-wrapper">
                                <img src="{{ getImage(getFilePath('store') . '/' . $item->image) }}" alt="{{ __($item->name) }}">
                            </div>
                            <div class="tc-badge-pill">
                                <i class="las la-gift"></i> @lang('GIFT COUPON')
                            </div>
                        </div>
                        <div class="tc-bottom">
                            <div>
                                <div class="tc-brand">{{__($item->name)}}</div>
                                
                                <div class="tc-desc">
                                    @if($item->description)
                                        <i class="las la-bolt"></i>{{ __($item->description) }}
                                    @else
                                        <i class="las la-tag"></i>@lang('Verified Promo Codes & Offers')
                                    @endif
                                </div>
                                
                                <div class="tc-coupon-box">
                                    <i class="las la-ticket-alt"></i>
                                    <span>{{ $item->coupons->count() }} @lang('Coupons')</span>
                                </div>
                            </div>
                            
                            <div class="tc-footer-action">
                                <div class="tc-avail">
                                    <i class="las la-check-circle"></i>
                                    <span>{{ $item->coupons->where('status', 1)->count() }} @lang('Active')</span>
                                </div>
                                <div class="tc-explore-btn">
                                    <span>@lang('Explore')</span>
                                    <i class="las la-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-lg-12 text-end">
                <a href="{{route('feature.store')}}" class="view-more-link">@lang('View More')</a>
            </div>
        </div>
    </div>
</section>