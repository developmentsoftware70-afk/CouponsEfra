@php
    $popularBrandsContent = getContent('popular_brands.content', true);
    $popularBrandsElements = getContent('popular_brands.element');

    // Priority map for brands: higher score = displayed first
    $priorityWeights = [
        'amazon' => 1000,
        'flipkart' => 950,
        'hyphen' => 900,
        'bacca bucci' => 850,
        'baccabucci' => 850,
        'myntra' => 800,
        'nykaa' => 750,
        'firstcry' => 700,
        'neemans' => 650,
        'dot & key' => 600,
        'dot and key' => 600,
        'wakefit' => 550,
        'cashify' => 500,
        'healthkart' => 450,
        'mcaffeine' => 400,
        'wow skin' => 350,
        'noise' => 300,
        'noice' => 300,
        'nilkamal' => 250,
        'nilkamal furniture' => 250,
        'the man company' => 200,
        'daily object' => 150,
        'daily objects' => 150,
        'igp' => 100,
        'furlenco' => 90,
        'guvi' => 80,
        'bajaj finserv' => 70,
        'search my journey' => 60,
        'stockedge' => 50,
    ];

    $brandsList = collect();

    // 1. Load brands configured in Admin Panel > Manage Frontend > Most Popular Brands
    if ($popularBrandsElements && $popularBrandsElements->count() > 0) {
        foreach ($popularBrandsElements as $idx => $elem) {
            $bName = @$elem->data_values->brand_name;
            $kName = strtolower(trim($bName));
            // Priority preserved from admin ordering / priority map
            $score = $priorityWeights[$kName] ?? (990 - $idx * 10);
            $brandsList->push((object)[
                'name' => $bName,
                'sale_tag' => @$elem->data_values->sale_tag ?: 'Sale Live Now',
                'cashback_text' => @$elem->data_values->cashback_text ?: 'Upto 7% Cashback',
                'image' => getImage('assets/images/frontend/popular_brands/' . @$elem->data_values->brand_logo),
                'link' => @$elem->data_values->link ?: 'javascript:void(0)',
                'priority' => $score
            ]);
        }
    }

    // 2. Also load active stores from DB (deduplicated by name)
    $existingNames = $brandsList->pluck('name')->map(function($n) { return strtolower(trim($n)); })->all();
    $activeStores = App\Models\Store::where('status', 1)->get()->unique(function($s) {
        return strtolower(trim($s->name));
    });

    foreach ($activeStores as $st) {
        $k = strtolower(trim($st->name));
        if (!in_array($k, $existingNames)) {
            $coupon = $st->coupons()->where('status', 1)->first();
            $cashbackText = ($coupon && !empty($coupon->cashback_amount)) 
                ? 'Upto ' . $coupon->cashback_amount . '% Cashback' 
                : 'Upto 5% Cashback';

            $brandsList->push((object)[
                'name' => $st->name,
                'sale_tag' => 'Sale Live Now',
                'cashback_text' => $cashbackText,
                'image' => getImage(getFilePath('store') . '/' . $st->image),
                'link' => route('store.coupons', $st->id),
                'priority' => $priorityWeights[$k] ?? 10
            ]);
        }
    }

    // Sort by priority descending (Amazon, Flipkart, Hyphen, Bacca Bucci, Myntra, Nykaa...)
    // Take top 18 cards for the homepage (3 rows of 6 columns)
    $homepageBrands = $brandsList->sortByDesc('priority')->values()->take(18);
@endphp

@push('style')
<style>
    /* Most Popular Brands Section */
    .popular-brands-section {
        padding-top: 28px;
        padding-bottom: 24px;
        margin-top: 10px;
        margin-bottom: 20px;
        position: relative;
    }

    /* Section Header: Title on Left, View All on Right */
    .pop-brands-header-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }
    .pop-brands-view-all {
        font-size: 14px;
        font-weight: 600;
        color: #0047ff !important;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
        padding: 4px 8px;
        border-radius: 6px;
    }
    .pop-brands-view-all:hover {
        color: #0036cc !important;
        transform: translateX(3px);
    }
    .pop-brands-view-all i {
        font-size: 12px;
        transition: transform 0.25s ease;
    }
    .pop-brands-view-all:hover i {
        transform: translateX(3px);
    }

    /* 3 Rows x 6 Columns = Compact, proportionate cards matching CashKaro reference */
    .pop-brands-grid-responsive {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        grid-gap: 15px;
    }
    .pop-brand-grid-item {
        outline: none !important;
        height: 100%;
    }

    /* Brand Card Container - Width ~190px, Height ~205px */
    .pop-brand-card {
        background: #ffffff;
        border: 1.5px solid #edf2f7;
        border-radius: 18px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
        text-decoration: none !important;
        height: 205px;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
    }
    .pop-brand-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 26px rgba(0, 71, 255, 0.14), 0 4px 10px rgba(0, 0, 0, 0.04);
        border-color: #bfdbfe;
    }

    /* Top Sale Tag / Ribbon */
    .pop-brand-tag {
        background: #ffe4e6;
        color: #e11d48;
        font-size: 11.5px;
        font-weight: 700;
        text-align: center;
        padding: 7px 8px;
        letter-spacing: 0.2px;
        border-radius: 16px 16px 0 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Center Brand Logo Wrap */
    .pop-brand-logo-wrap {
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px 14px;
        flex-grow: 1;
    }
    .pop-brand-logo-wrap img {
        max-width: 125px;
        max-height: 52px;
        width: auto;
        height: auto;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    .pop-brand-card:hover .pop-brand-logo-wrap img {
        transform: scale(1.06);
    }

    /* Bottom Blue Cashback Button */
    .pop-brand-btn-wrap {
        padding: 0 12px 14px 12px;
    }
    .pop-brand-btn {
        background: #0047ff;
        color: #ffffff !important;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-align: center;
        padding: 9px 10px;
        display: block;
        box-shadow: 0 2px 6px rgba(0, 71, 255, 0.25);
        transition: all 0.2s ease;
        text-decoration: none !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .pop-brand-card:hover .pop-brand-btn {
        background: #0036cc;
        box-shadow: 0 4px 12px rgba(0, 71, 255, 0.35);
    }

    /* Responsive adjustments */
    @media (max-width: 1399px) {
        .pop-brands-grid-responsive {
            grid-template-columns: repeat(6, 1fr);
            grid-gap: 12px;
        }
        .pop-brand-card {
            height: 195px;
        }
    }
    @media (max-width: 1199px) {
        .pop-brands-grid-responsive {
            grid-template-columns: repeat(5, 1fr);
            grid-gap: 12px;
        }
    }
    @media (max-width: 991px) {
        .pop-brands-grid-responsive {
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 12px;
        }
    }
    @media (max-width: 600px) {
        .popular-brands-section {
            padding-top: 20px;
            padding-bottom: 18px;
        }
        .pop-brands-grid-responsive {
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 10px;
        }
        .pop-brand-card {
            height: 165px;
            border-radius: 14px;
        }
        .pop-brand-tag {
            font-size: 10.5px;
            padding: 4px 6px;
        }
        .pop-brand-logo-wrap {
            height: 68px;
            padding: 6px 10px;
        }
        .pop-brand-logo-wrap img {
            max-width: 95px;
            max-height: 38px;
        }
        .pop-brand-btn-wrap {
            padding: 0 8px 10px 8px;
        }
        .pop-brand-btn {
            font-size: 11.5px;
            padding: 7px 6px;
            border-radius: 8px;
        }
    }
</style>
@endpush

<section class="popular-brands-section">
    <div class="container-fluid container-custom">
        {{-- Section Header with View All --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="pop-brands-header-wrap">
                    <h2 class="section-title mb-0 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                        {{ __($popularBrandsContent->data_values->heading ?? 'Most Popular Brands') }}
                    </h2>
                    <a href="{{ route('brands') }}" class="pop-brands-view-all">
                        @lang('View All') <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Brands Responsive Grid (No Carousel / No <> Arrows) --}}
        <div class="pop-brands-grid-responsive">
            @foreach($homepageBrands as $brand)
            <div class="pop-brand-grid-item">
                <a href="{{ $brand->link }}" class="pop-brand-card">
                    <div class="pop-brand-tag">
                        {{ __($brand->sale_tag) }}
                    </div>
                    <div class="pop-brand-logo-wrap">
                        <img src="{{ $brand->image }}" alt="{{ __($brand->name) }}" loading="lazy">
                    </div>
                    <div class="pop-brand-btn-wrap">
                        <span class="pop-brand-btn">
                            {{ __($brand->cashback_text) }}
                        </span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        {{-- Bottom View More Link (Consistent with other sections) --}}
        <div class="row pt-3">
            <div class="col-lg-12 text-end">
                <a href="{{ route('brands') }}" class="view-more-link">@lang('View More')</a>
            </div>
        </div>
    </div>
</section>
