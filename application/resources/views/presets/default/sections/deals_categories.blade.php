@php
    $dealsCategory = getContent('deals_categories.content', true);
    $dealsCategoryElements = App\Models\DealCategory::with('deals')
        ->where('is_show', 1)
        ->where('status', 1)
        ->latest()
        ->limit(8)
        ->get();
@endphp

<section class="top-collections top-stores second py-20">
    <div class="container-fluid container-custom">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                            {{ __($dealsCategory->data_values->heading) }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .dc-card {
                background: #ffffff;
                border-radius: 20px;
                border: 1px solid #cbd5e1; /* highlighted border */
                box-shadow: none; /* shadow background removed */
                padding: 25px 15px 20px;
                text-align: center;
                transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-decoration: none !important;
                height: 100%;
                margin: 0; 
                position: relative;
                will-change: transform;
            }
            .store-slider .slick-list {
                padding-top: 10px !important;
                padding-bottom: 10px !important;
            }
            .store-slider .slick-slide-inner {
                padding: 5px 10px; /* Reduced from 15px to account for the list padding */
            }
            .dc-card:hover {
                transform: translateY(-4px);
                box-shadow: none;
                border: 1px solid #94a3b8 !important; /* Keep border visible on hover */
            }
            .dc-logo-wrap {
                width: 90px;
                height: 90px;
                background: #ffffff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 25px rgba(0,0,0,0.08); /* circle shadow kept */
                margin-bottom: 20px;
                position: relative;
                z-index: 1;
                padding: 5px; /* bigger logo */
                transition: transform 0.4s ease;
            }
            .dc-card:hover .dc-logo-wrap {
                transform: scale(1.08);
            }
            .dc-logo-wrap img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                border-radius: 50%;
            }
            .dc-title {
                font-size: 18px; /* smaller title */
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 15px;
                position: relative;
                z-index: 1;
                width: 100%;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .dc-badge {
                display: inline-block;
                padding: 6px 14px;
                border-radius: 30px;
                font-size: 12px;
                font-weight: 700;
                color: #ffffff;
                position: relative;
                z-index: 1;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                box-shadow: none; /* button shadow removed */
            }
            
            /* Badge colors matching gradients without shadows - uniform color */
            .dc-badge { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        </style>
        <div class="row">
            <div class="col-md-12">
                <div class="slider-wrapper" style="margin-bottom: 0;">
                    <div class="store-slider" style="margin-bottom: 0;">
                        @foreach($dealsCategoryElements as $index => $item)
                            <div class="slick-slide-inner">
                                <a href="{{ route('category.deals', $item->id) }}" class="dc-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                                    <div class="dc-logo-wrap">
                                        <img src="{{ getImage(getFilePath('dealCategory') . '/' . $item->image) }}" alt="{{ __($item->title) }}">
                                    </div>
                                    <div class="dc-title">
                                        {{ __($item->title) }}
                                    </div>
                                    <div class="dc-badge">
                                        {{ $item->deals->count() }} @lang('Active Deals')
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row pt-0">
            <div class="col-lg-12 text-end" style="margin-bottom: 0;">
                <a href="{{ route('deals.categories') }}" class="view-more-link" style="margin-bottom: 5px; display: inline-block;">@lang('View More')</a>
            </div>
        </div>
    </div>
</section>
