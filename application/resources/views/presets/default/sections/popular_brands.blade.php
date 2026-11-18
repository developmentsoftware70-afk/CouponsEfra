@php
    /*
    |--------------------------------------------------------------------------
    | Most Popular Brands — Hybrid Tier Architecture (3 Rows x 6 Cols = 18)
    | (Documentation: most_popular_brands_logic_documentation.md)
    |--------------------------------------------------------------------------
    |
    | TIER 1 — Admin Priority / Anchor Giants: All active stores with
    |           priority_score > 0 sorted by priority_score DESC
    |           (Amazon=1000, Flipkart=950, Myntra=900, Nykaa=850, Firstcry=700,
    |           plus any store admin assigns a priority_score in Admin -> Stores).
    |
    | TIER 2 — Dynamic Engagement Rank: Remaining active stores (priority = 0)
    |           ranked by Hybrid Score = (click_count * 0.2) + (cashback * 0.3)
    |
    | Total display: 18 brands (3 rows x 6 columns on desktop)
    |--------------------------------------------------------------------------
    */

    $totalDisplayLimit     = 18;
    $popularBrandsContent  = getContent('popular_brands.content', true);
    $popularBrandsElements = getContent('popular_brands.element');

    // Load all active stores with their active coupons
    $allStores = App\Models\Store::where('status', 1)->with(['coupons' => function($q) {
        $q->where('status', 1);
    }])->get();

    // Map curated metadata (custom logo, sale tag, fallback cashback) from popular_brands.element to store_id
    // Pass 1: exact normalized name match first so prefix matches never overwrite an exact store
    $elementMetaByStoreId = [];
    $unmatchedElements    = collect();

    if ($popularBrandsElements && $popularBrandsElements->count() > 0) {
        $pendingPrefixElements = [];

        foreach ($popularBrandsElements as $elem) {
            $bName = @$elem->data_values->brand_name;
            $kName = strtolower(trim((string) $bName));
            if ($kName === '') {
                continue;
            }

            $exactMatch = $allStores->first(function($st) use ($kName) {
                return strtolower(trim($st->name)) === $kName;
            });

            if ($exactMatch) {
                if (!isset($elementMetaByStoreId[$exactMatch->id])) {
                    $elementMetaByStoreId[$exactMatch->id] = $elem;
                }
            } else {
                $pendingPrefixElements[] = [$elem, $kName];
            }
        }

        // Pass 2: fallback to prefix match only for stores that did not already have an exact match
        foreach ($pendingPrefixElements as [$elem, $kName]) {
            $prefixMatch = $allStores->first(function($st) use ($kName, $elementMetaByStoreId) {
                if (isset($elementMetaByStoreId[$st->id])) {
                    return false;
                }
                $stName = strtolower(trim($st->name));
                return str_starts_with($stName, $kName) || str_starts_with($kName, $stName);
            });

            if ($prefixMatch) {
                $elementMetaByStoreId[$prefixMatch->id] = $elem;
            } else {
                $unmatchedElements->push($elem);
            }
        }
    }

    // Helper closure to build a standardized brand card object for any Store
    $buildBrandCard = function($st) use ($elementMetaByStoreId) {
        $elem = $elementMetaByStoreId[$st->id] ?? null;

        // Parse best coupon cashback safely (numeric extraction)
        $bestCashbackRaw = 0;
        foreach ($st->coupons as $cp) {
            $val = (float) preg_replace('/[^0-9.]/', '', (string) $cp->cashback_amount);
            if ($val > $bestCashbackRaw) {
                $bestCashbackRaw = $val;
            }
        }

        $clickCount    = (int) $st->coupons->sum('click_count');
        $priorityScore = (int) ($st->priority_score ?? 0);

        // Cap cashback contribution at 30 so flat rupee amounts don't overpower clicks
        $cashbackScore = min($bestCashbackRaw, 30);
        $hybridScore   = ($clickCount * 0.2) + ($cashbackScore * 0.3) + ($priorityScore * 0.5);

        // Format cashback button text cleanly
        if ($bestCashbackRaw > 100) {
            $cashbackText = 'Upto ₹' . number_format($bestCashbackRaw, 0) . ' Cashback';
        } elseif ($bestCashbackRaw > 0) {
            $fmt = fmod($bestCashbackRaw, 1) == 0 ? (int) $bestCashbackRaw : $bestCashbackRaw;
            $cashbackText = 'Upto ' . $fmt . '% Cashback';
        } elseif ($elem && !empty($elem->data_values->cashback_text)) {
            $cashbackText = $elem->data_values->cashback_text;
        } else {
            $cashbackText = 'Visit Store';
        }

        // Resolve logo: prefer curated popular_brands logo if file exists, otherwise store logo from assets/images/frontend/store/
        $logoFile = $elem ? @$elem->data_values->brand_logo : null;
        $popRel   = 'assets/images/frontend/popular_brands/' . $logoFile;
        $storeRel = getFilePath('store') . '/' . $st->image;

        if (!empty($logoFile) && file_exists(base_path('../' . $popRel))) {
            $imageUrl = getImage($popRel);
        } elseif (!empty($st->image) && file_exists(base_path('../' . $storeRel))) {
            $imageUrl = getImage($storeRel);
        } else {
            $imageUrl = getImage($storeRel);
        }

        return (object)[
            'store_id'       => $st->id,
            'name'           => ($elem && !empty($elem->data_values->brand_name)) ? $elem->data_values->brand_name : $st->name,
            'sale_tag'       => ($elem && !empty($elem->data_values->sale_tag)) ? $elem->data_values->sale_tag : 'Sale Live Now',
            'cashback_text'  => $cashbackText,
            'image'          => $imageUrl,
            'link'           => route('store.coupons', $st->id),
            'priority_score' => $priorityScore,
            'has_coupons'    => $st->coupons->count() > 0 ? 1 : 0,
            'hybrid_score'   => $hybridScore,
        ];
    };

    $allBrandCards = $allStores->map($buildBrandCard);

    // ── TIER 1: All stores with Admin priority_score > 0 (sorted highest priority first) ──
    $tier1Brands = $allBrandCards
        ->filter(fn($b) => $b->priority_score > 0)
        ->sortByDesc('priority_score')
        ->values()
        ->take($totalDisplayLimit);

    $tier1StoreIds = $tier1Brands->pluck('store_id')->toArray();

    // ── TIER 2: Remaining stores (priority = 0) ranked by dynamic clicks + cashback score ──
    $slotsLeft = $totalDisplayLimit - $tier1Brands->count();
    $tier2Brands = collect();

    if ($slotsLeft > 0) {
        $tier2Brands = $allBrandCards
            ->whereNotIn('store_id', $tier1StoreIds)
            ->sort(function($a, $b) {
                // Prioritize stores with active coupons first, then highest hybrid_score
                if ($a->has_coupons !== $b->has_coupons) {
                    return $b->has_coupons <=> $a->has_coupons;
                }
                return $b->hybrid_score <=> $a->hybrid_score;
            })
            ->take($slotsLeft)
            ->values();
    }

    // ── Merge Tier 1 + Tier 2 = 18 Brands (3 Rows x 6 Columns) ──
    $homepageBrands = $tier1Brands->concat($tier2Brands);
@endphp

@push('style')
<style>
    /* Most Popular Brands Section */
    .popular-brands-section {
        padding-top: 28px;
        padding-bottom: 10px;
        margin-top: 10px;
        margin-bottom: 0px;
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
        font-size: 14px !important;
        font-weight: 600 !important;
        line-height: 1.4 !important;
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
    </div>
</section>
