@extends($activeTemplate.'layouts.frontend')
@section('content')

@push('style')
<style>
    /* Brands Page Wrapper */
    .brands-page-section {
        padding-top: 40px;
        padding-bottom: 50px;
    }

    /* Brands Page Grid */
    .all-brands-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        grid-gap: 16px;
    }

    /* Brand Card Container */
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
        .all-brands-grid {
            grid-template-columns: repeat(6, 1fr);
            grid-gap: 14px;
        }
        .pop-brand-card {
            height: 195px;
        }
    }
    @media (max-width: 1199px) {
        .all-brands-grid {
            grid-template-columns: repeat(5, 1fr);
            grid-gap: 12px;
        }
    }
    @media (max-width: 991px) {
        .all-brands-grid {
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 12px;
        }
    }
    @media (max-width: 600px) {
        .brands-page-section {
            padding-top: 25px;
            padding-bottom: 30px;
        }
        .all-brands-grid {
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

<section class="brands-page-section">
    <div class="container-fluid container-custom">
        {{-- Page Header --}}
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="section-content">
                    <div class="title-wrap">
                        <h2 class="section-title mb-2">
                            @lang('Most Popular Brands')
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- All Brands Grid --}}
        <div class="all-brands-grid">
            @php
                $curatedBrandMeta = [
                    'amazon' => ['tag' => 'Sale Live Now', 'cashback' => 'Upto 7% Rewards'],
                    'flipkart' => ['tag' => 'Sale Live Now', 'cashback' => 'Upto 6.50% Cashback'],
                    'hyphen' => ['tag' => 'Buy 2 @ 799', 'cashback' => 'Flat 10% Cashback'],
                    'bacca bucci' => ['tag' => 'Sale Live Now', 'cashback' => 'Flat 15% Cashback'],
                    'myntra' => ['tag' => 'Sale Live Now', 'cashback' => 'Upto 6% Cashback'],
                    'nykaa' => ['tag' => 'Sale Live Now', 'cashback' => 'Upto 6% Cashback'],
                    'firstcry' => ['tag' => 'Upto 85% Off', 'cashback' => 'Upto ₹30 Cashback'],
                    'neemans' => ['tag' => 'Sale Live Now', 'cashback' => 'Upto 12% Cashback'],
                    'dot & key' => ['tag' => 'Sale Live Now', 'cashback' => 'Flat 10% Cashback'],
                    'wakefit' => ['tag' => 'Sale Live Now', 'cashback' => 'Upto 7% Cashback'],
                    'cashify' => ['tag' => 'Sale Live Now', 'cashback' => 'Upto 6% Cashback'],
                    'healthkart' => ['tag' => 'Upto 90% Off', 'cashback' => 'Upto 8% Cashback'],
                    'mcaffeine' => ['tag' => 'Sale Live Now', 'cashback' => 'Flat 15% Cashback'],
                    'wow skin' => ['tag' => 'Sale Live Now', 'cashback' => 'Flat 12% Cashback'],
                    'noice' => ['tag' => 'Sale Live Now', 'cashback' => 'Upto 7% Cashback'],
                    'nilkamal furniture' => ['tag' => 'Sale Live Now', 'cashback' => 'Upto 8% Cashback'],
                    'the man company' => ['tag' => 'Sale Live Now', 'cashback' => 'Flat 10% Cashback'],
                    'daily object' => ['tag' => 'Sale Live Now', 'cashback' => 'Upto 6% Cashback'],
                ];
                $saleTagOptions = ['Sale Live Now', 'Upto 90% Off', 'Sale Live Now', 'Upto 85% Off', 'Sale Live Now', 'Flat 50% Off'];
            @endphp
            @foreach($stores as $idx => $store)
            @php
                $k = strtolower(trim($store->name));
                $meta = $curatedBrandMeta[$k] ?? null;
                $coupon = $store->coupons->where('status', 1)->first();

                if ($meta) {
                    $saleTag = $meta['tag'];
                    $cashbackText = $meta['cashback'];
                } else {
                    $saleTag = $saleTagOptions[$idx % count($saleTagOptions)];
                    $cashbackText = ($coupon && !empty($coupon->cashback_amount)) 
                        ? 'Upto ' . $coupon->cashback_amount . '% Cashback' 
                        : 'Upto ' . (5 + ($idx % 5) * 2) . '% Cashback';
                }
            @endphp
            <div>
                <a href="{{ route('store.coupons', $store->id) }}" class="pop-brand-card">
                    <div class="pop-brand-tag">
                        {{ __($saleTag) }}
                    </div>
                    <div class="pop-brand-logo-wrap">
                        <img src="{{ getImage(getFilePath('store') . '/' . $store->image) }}" alt="{{ __($store->name) }}" loading="lazy">
                    </div>
                    <div class="pop-brand-btn-wrap">
                        <span class="pop-brand-btn">
                            {{ __($cashbackText) }}
                        </span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="row py-4">
            @if ($stores->hasPages())
                <div class="py-4">
                    {{ paginateLinks($stores) }}
                </div>
            @endif
        </div>
    </div>
</section>

@endsection

