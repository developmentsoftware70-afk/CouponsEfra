@extends($activeTemplate.'layouts.frontend')
@section('content')

@php
    $finalTargetLink = appendTrackingSource($coupon->link);
@endphp

<!-- ==========================================
     CASHKARO STYLE COMPACT TOP STAGE
     ========================================== -->
<section class="ck-top-stage-section">
    {{-- Sleek Tech Dot-Grid & Centered Ambient Spotlight --}}
    <div class="ck-tech-grid-bg">
        <div class="ck-tech-grid-pattern"></div>
        <div class="ck-center-ambient-glow"></div>
    </div>

    <div class="container position-relative" style="z-index: 2;">
        {{-- Back Navigation --}}
        <div class="ck-top-nav-row">
            <a href="{{ url()->previous() == url()->current() ? route('home') : url()->previous() }}" class="ck-back-link">
                <i class="fas fa-arrow-left me-1"></i> @lang('Back to Deals')
            </a>
        </div>

        {{-- Centered CashKaro Style Amber/Golden Card --}}
        <div class="ck-center-stage-wrapper">
            <div class="ck-amber-offer-card">
                <div class="ck-amber-card-left">
                    {{-- Store Logo Pill & Star --}}
                    <div class="d-flex align-items-center justify-content-between w-100 mb-2">
                        <div class="ck-store-logo-pill">
                            @if($coupon->store && $coupon->store->image)
                                <img src="{{ getImage(getFilePath('store').'/'.$coupon->store->image) }}" alt="{{ $coupon->store->name }}">
                            @elseif($coupon->store)
                                <span class="ck-store-text">{{ Str::limit($coupon->store->name, 14) }}</span>
                            @else
                                <span class="ck-store-text">@lang('EXCLUSIVE')</span>
                            @endif
                        </div>
                        <span class="ck-card-star-icon" title="@lang('Featured Deal')">
                            <i class="fas fa-star text-warning"></i>
                        </span>
                    </div>

                    {{-- Title & Subtitle --}}
                    <h1 class="ck-card-main-title">{{ $coupon->title }}</h1>
                    @if(strlen(strip_tags($coupon->description)) > 0)
                        <p class="ck-card-sub-info">
                            {{ Str::limit(html_entity_decode(strip_tags($coupon->description)), 50) }}
                        </p>
                    @endif

                    {{-- CashKaro Style Cashback Badge Pill --}}
                    <div class="ck-cashback-pill-box">
                        <span class="ck-cb-brand-tag">CK</span>
                        <span class="ck-cb-text">
                            @if($coupon->is_cashback == 1)
                                Upto {{ $coupon->cashback_amount }}% Cashback
                            @else
                                Verified Direct Deal
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Product / Deal Cutout Image on Right --}}
                <div class="ck-amber-card-right">
                    @if($coupon->image)
                        <img src="{{ getImage(getFilePath('coupon').'/'.$coupon->image) }}" alt="{{ $coupon->title }}">
                    @elseif($coupon->store && $coupon->store->image)
                        <img src="{{ getImage(getFilePath('store').'/'.$coupon->store->image) }}" alt="{{ $coupon->store->name }}">
                    @else
                        <div class="ck-placeholder-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    @endif
                </div>
            </div>

            {{-- If coupon code exists: compact copy code pill --}}
            @if($coupon->code)
                <div class="ck-compact-code-row">
                    <span class="ck-compact-code-label">@lang('Coupon Code'):</span>
                    <span class="ck-compact-code-val" id="couponCodeDisplay">{{ $coupon->code }}</span>
                    <button type="button" class="ck-compact-code-copy" onclick="copyCouponCode('{{ $coupon->code }}')">
                        <span id="copyCodeBtnLabel">@lang('Copy')</span>
                        <i class="far fa-copy ms-1"></i>
                    </button>
                </div>
            @endif

            {{-- Vibrant Orange CashKaro Grab Deal Button --}}
            <a href="{{ route('coupon.redirect', $coupon->id) }}" target="_blank" class="ck-orange-grab-btn" onclick="trackClickCount()">
                @lang('Grab Deal') <span class="ms-1">&rarr;</span>
            </a>

            {{-- Share Link: Copies Link & Opens Social Share Modal --}}
            <div class="text-center mt-2">
                <button type="button" class="ck-share-link-btn" onclick="openShareModal('{{ $finalTargetLink }}', '{{ addslashes($coupon->title) }}', this)">
                    <i class="fas fa-share-alt me-1"></i> @lang('Copy Shareable Link')
                </button>
            </div>
        </div>
    </div>
</section>

<div class="ck-section-divider"></div>

<!-- ==========================================
     MAIN CONTENT & RELATED OFFERS LAYOUT
     Left side: Content starts from left (~78%)
     Right side: Related Offers sidebar (~22%)
     ========================================== -->
<section class="ck-editorial-section">
    <div class="container">
        <div class="ck-detail-main-layout">
            
            <!-- LEFT COLUMN: Main Text Details & Offer Info (Starts from left) -->
            <div class="ck-content-left-col">
                {{-- Section 1: Deal Title & Highlights --}}
                <div class="ck-content-block">
                    <h3 class="ck-block-heading-with-icon">
                        <span class="ck-emoji">🔥</span> {{ $coupon->title }}
                    </h3>
                    <ul class="ck-bullet-list">
                        <li>@lang('Elevate your savings with verified discounts from') <strong>{{ @$coupon->store->name ?? 'our partner' }}</strong></li>
                        @if($coupon->code)
                            <li>@lang('Use coupon code') <strong class="text-primary">{{ $coupon->code }}</strong> @lang('during checkout for instant discount')</li>
                        @else
                            <li>@lang('No coupon code required — direct discount will be automatically applied on checkout')</li>
                        @endif
                        <li>@lang('Applicable on selected and qualifying categories')</li>
                        <li>@lang('Tested and verified by our deals curation team')</li>
                    </ul>
                </div>

                {{-- Section 2: Why you'll like it --}}
                <div class="ck-content-block">
                    <h4 class="ck-block-sub-heading">@lang("Why you'll like it")</h4>
                    <ul class="ck-bullet-list">
                        <li>@lang('Instant discount applied at merchant checkout with zero extra hassle')</li>
                        <li>@lang('Genuine brand products directly from the official store')</li>
                        <li>@lang('Compatible with standard online payment modes including UPI, Credit Cards, Debit Cards and Netbanking')</li>
                        <li>@lang('Safe and secure checkout through official merchant gateway')</li>
                    </ul>
                </div>

                {{-- Section 3: Cashback Details --}}
                <div class="ck-content-block">
                    <h3 class="ck-block-heading-with-icon">
                        <span class="ck-emoji">🪙</span> 
                        @if($coupon->is_cashback == 1)
                            Flat {{ $coupon->cashback_amount }}% Cashback, @lang('over and above the deal')
                        @else
                            Special Partner Savings, @lang('over and above the deal')
                        @endif
                    </h3>
                    <ul class="ck-bullet-list">
                        @if($coupon->is_cashback == 1)
                            <li>Flat <strong>{{ $coupon->cashback_amount }}%</strong> Cashback — @lang('over and above all discounts, tracked to your account and withdrawable to your bank.')</li>
                        @else
                            <li>@lang('Earn reward points and partner credits directly to your account, withdrawable to your bank or gift cards.')</li>
                        @endif
                        <li>@lang('Tracking Time:') <strong>@lang('5 Minutes')</strong></li>
                        <li>@lang('Confirmation Timeline:') <strong>{{ $coupon->store->cashback_confirmation_days ?? '50' }} @lang('Days')</strong></li>
                    </ul>
                </div>

                {{-- Section 4: Grab it in 3 steps --}}
                <div class="ck-content-block">
                    <h4 class="ck-block-sub-heading">@lang('Grab it in 3 steps')</h4>
                    <ul class="ck-bullet-list">
                        <li>@lang('1. Tap the orange') <strong>@lang('Grab Deal')</strong> @lang('button above to visit') {{ @$coupon->store->name ?? 'the store' }} @lang('via our platform.')</li>
                        <li>@lang('2. Browse items, add to cart and apply coupon code') <strong>{{ $coupon->code ?? 'if applicable' }}</strong> @lang('on the checkout page.')</li>
                        <li>@lang('3. Complete your payment as usual. Your cashback will track automatically within 5 minutes!')</li>
                    </ul>
                </div>

                {{-- Section 5: Good to know before you buy --}}
                <div class="ck-content-block">
                    <h4 class="ck-block-sub-heading">@lang('Good to know before you buy')</h4>
                    <ul class="ck-bullet-list">
                        <li>@lang('Cashback is only applicable on purchases completed in the exact tab opened after clicking Grab Deal.')</li>
                        <li>@lang('Cashback is calculated on the net purchase value, excluding GST, delivery charges, platform convenience fees, and wallet credits.')</li>
                        <li>@lang('If your Cashback does not track within 24 hours, you can raise a Missing Cashback ticket within 30 days of the purchase date.')</li>
                        <li>@lang('Deal prices, product availability and coupon validity are dynamic and subject to the merchant\'s live pricing at checkout.')</li>
                    </ul>
                </div>

                {{-- Section 6: Render existing custom description/tables if available --}}
                @if(strlen(strip_tags($coupon->description)) > 150)
                    <div class="ck-content-block ck-extra-description mt-4 pt-3 border-top">
                        <h4 class="ck-block-sub-heading mb-3">@lang('Offer Details & Terms')</h4>
                        <div class="ck-rich-description-body" id="dealArticleBody">
                            {!! $coupon->description !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- RIGHT COLUMN: Related Offers (~22% width, sticky) -->
            <aside class="ck-sidebar-right-col">
                <div class="ck-sidebar-card">
                    <div class="ck-sidebar-header">
                        <div class="d-flex align-items-center gap-2">
                            <span class="ck-sidebar-fire-icon"><i class="fas fa-fire text-danger"></i></span>
                            <div>
                                <h5 class="ck-sidebar-title mb-0">
                                    @if(@$coupon->category)
                                        {{ @$coupon->category->name }} @lang('Offers')
                                    @else
                                        {{ @$coupon->store->name ?? 'Store' }} @lang('Offers')
                                    @endif
                                </h5>
                                <span class="ck-sidebar-subtitle">
                                    @if(@$coupon->category)
                                        @lang('Top brand deals in') {{ @$coupon->category->name }}
                                    @else
                                        @lang('Active & related deals')
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="ck-sidebar-list">
                        @forelse($relatedCoupons as $rel)
                            <div class="ck-sidebar-item">
                                <div class="ck-sidebar-item-top">
                                    <div class="ck-sidebar-store-tag">
                                        @if($rel->store && $rel->store->image)
                                            <img src="{{ getImage(getFilePath('store').'/'.$rel->store->image) }}" alt="{{ $rel->store->name }}" class="ck-sidebar-store-img">
                                        @endif
                                        <span>{{ Str::limit($rel->store->name ?? 'Store', 14) }}</span>
                                    </div>
                                    <span class="ck-sidebar-badge">
                                        @if($rel->is_cashback == 1)
                                            {{ $rel->cashback_amount }}% @lang('CB')
                                        @else
                                            @lang('DEAL')
                                        @endif
                                    </span>
                                </div>
                                <h6 class="ck-sidebar-item-title">
                                    <a href="{{ route('coupon.details', $rel->id) }}">
                                        {{ Str::limit($rel->title, 42) }}
                                    </a>
                                </h6>
                                <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top" style="border-color: #f1f5f9 !important;">
                                    <span class="ck-sidebar-uses">
                                        <i class="far fa-eye me-1"></i>{{ $rel->click_count ?? 0 }} @lang('views')
                                    </span>
                                    <a href="{{ route('coupon.details', $rel->id) }}" class="ck-sidebar-grab-btn">
                                        @lang('Grab Deal') <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-center text-muted" style="font-size: 13px;">
                                <i class="fas fa-tag fa-2x mb-2 text-muted opacity-50"></i>
                                <p class="mb-0">@lang('No other offers right now')</p>
                            </div>
                        @endforelse
                    </div>

                    @if($coupon->category_id)
                        <div class="ck-sidebar-footer">
                            <a href="{{ route('category.coupons', $coupon->category_id) }}" class="ck-sidebar-all-link">
                                @lang('View All') {{ @$coupon->category->name }} @lang('Offers') &rarr;
                            </a>
                        </div>
                    @elseif($coupon->store_id)
                        <div class="ck-sidebar-footer">
                            <a href="{{ route('store.coupons', $coupon->store_id) }}" class="ck-sidebar-all-link">
                                @lang('View All') {{ @$coupon->store->name }} @lang('Deals') &rarr;
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Trust Badges Box --}}
                <div class="ck-sidebar-trust-box mt-3">
                    <div class="ck-trust-item">
                        <i class="fas fa-shield-alt text-success me-2"></i>
                        <span>@lang('100% Verified Merchant Deals')</span>
                    </div>
                    <div class="ck-trust-item">
                        <i class="fas fa-bolt text-warning me-2"></i>
                        <span>@lang('Instant Tracking in 5 Mins')</span>
                    </div>
                    <div class="ck-trust-item">
                        <i class="fas fa-university text-info me-2"></i>
                        <span>@lang('Direct Bank Transfer Cashback')</span>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</section>

<!-- ==========================================
     STYLES
     ========================================== -->
<style>
/* Hide default breadcrumb banner on full-bleed coupon details landing page */
.breadcumb {
    display: none !important;
}
/* CashKaro Style Top Stage - Sleek Tech Dot-Grid & Centered Ambient Spotlight */
.ck-top-stage-section {
    position: relative;
    background: #f8fafc;
    padding: 24px 0 28px;
    border-bottom: 1px solid #e2e8f0;
    overflow: hidden;
}

/* Background Wrapper */
.ck-tech-grid-bg {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
}

/* Modern High-Tech Crisp Dot-Grid Pattern with Smooth Vignette */
.ck-tech-grid-pattern {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(100, 116, 139, 0.22) 1.25px, transparent 1.25px);
    background-size: 22px 22px;
    background-position: center center;
    mask-image: radial-gradient(ellipse 70% 60% at 50% 50%, rgba(0,0,0,1) 30%, rgba(0,0,0,0) 85%);
    -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 50%, rgba(0,0,0,1) 30%, rgba(0,0,0,0) 85%);
}

/* Centered Ambient Spotlight - Perfectly Centered Behind Offer Card */
.ck-center-ambient-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 620px;
    height: 440px;
    max-width: 92vw;
    border-radius: 50%;
    background: radial-gradient(ellipse at center, rgba(245, 158, 11, 0.24) 0%, rgba(251, 191, 36, 0.10) 45%, rgba(245, 158, 11, 0) 72%);
    filter: blur(55px);
    animation: ckAmbientPulse 8s ease-in-out infinite alternate;
}

@keyframes ckAmbientPulse {
    0% {
        transform: translate(-50%, -50%) scale(0.96);
        opacity: 0.75;
    }
    100% {
        transform: translate(-50%, -50%) scale(1.08);
        opacity: 1;
    }
}

.ck-top-nav-row {
    margin-bottom: 14px;
}

.ck-back-link {
    display: inline-flex;
    align-items: center;
    color: #334155 !important;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none !important;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(8px);
    padding: 5px 14px;
    border-radius: 20px;
    border: 1px solid rgba(226, 232, 240, 0.9);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    transition: all 0.2s ease;
}

.ck-back-link:hover {
    color: #0f172a !important;
    background: #ffffff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}

/* Centered Stage Wrapper */
.ck-center-stage-wrapper {
    max-width: 410px;
    margin: 0 auto;
}

/* Golden Amber Offer Card (CashKaro Style - Scaled 410px x 265px, Ratio ~1.55) */
.ck-amber-offer-card {
    background: linear-gradient(175deg, #ea9423 0%, #d27d14 55%, #ba6401 100%);
    border-radius: 20px;
    padding: 18px 22px;
    position: relative;
    overflow: hidden;
    width: 100%;
    max-width: 410px;
    height: 265px;
    display: flex;
    justify-content: space-between;
    box-shadow: 0 12px 28px -6px rgba(186, 100, 1, 0.32);
    color: #ffffff;
    margin: 0 auto;
}

.ck-amber-card-left {
    flex: 1 1 58%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-start;
    padding-right: 10px;
    z-index: 2;
    min-width: 0;
}

.ck-store-logo-pill {
    padding: 4px 14px;
    border-radius: 10px;
    height: 40px;
    min-width: 78px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
}

.ck-store-logo-pill img {
    max-height: 24px;
    max-width: 74px;
    object-fit: contain;
}

.ck-store-text {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.ck-card-star-icon {
    font-size: 15px;
    color: #ffda6a;
    background: rgba(255, 255, 255, 0.18);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.ck-card-main-title {
    font-size: 21px;
    font-weight: 800;
    color: #ffffff !important;
    line-height: 1.22;
    margin: 8px 0 2px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
    letter-spacing: -0.2px;
}

.ck-card-sub-info {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.95);
    font-weight: 500;
    line-height: 1.35;
    margin: 0 0 8px;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.ck-cashback-pill-box {
    display: inline-flex;
    align-items: center;
    background: rgba(0, 0, 0, 0.16);
    border-radius: 8px;
    overflow: hidden;
    margin-top: auto;
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.ck-cb-brand-tag {
    background: #004fe6;
    color: #ffffff;
    font-size: 11.5px;
    font-weight: 900;
    padding: 5px 9px;
    letter-spacing: 0.5px;
}

.ck-cb-text {
    color: #ffffff;
    font-size: 12.5px;
    font-weight: 700;
    padding: 5px 11px;
}

.ck-amber-card-right {
    flex: 0 0 42%;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    position: relative;
    z-index: 1;
    height: 100%;
}

.ck-amber-card-right img {
    max-width: 100%;
    max-height: 228px;
    object-fit: contain;
    border-radius: 10px;
    filter: drop-shadow(0 6px 14px rgba(0, 0, 0, 0.25));
}

.ck-placeholder-icon {
    font-size: 48px;
    color: rgba(255, 255, 255, 0.35);
}

/* Compact Code Row */
.ck-compact-code-row {
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(8px);
    border: 1.5px dashed #3b82f6;
    border-radius: 10px;
    padding: 8px 16px;
    margin-top: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);
}

.ck-compact-code-label {
    font-size: 12px;
    font-weight: 600;
    color: #475569;
}

.ck-compact-code-val {
    font-size: 13.5px;
    font-weight: 800;
    color: #1d4ed8;
    letter-spacing: 1px;
    user-select: all;
}

.ck-compact-code-copy {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1d4ed8;
    font-size: 12px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.ck-compact-code-copy:hover {
    background: #dbeafe;
}

/* Vibrant Orange CashKaro Button */
.ck-orange-grab-btn {
    background: #ff5200;
    color: #ffffff !important;
    font-size: 16px;
    font-weight: 800;
    text-align: center;
    padding: 12px 24px;
    border-radius: 10px;
    display: block;
    width: 100%;
    margin-top: 12px;
    text-decoration: none !important;
    box-shadow: 0 4px 14px rgba(255, 82, 0, 0.35);
    transition: all 0.2s ease;
    letter-spacing: 0.3px;
}

.ck-orange-grab-btn:hover {
    background: #e04800;
    box-shadow: 0 6px 18px rgba(255, 82, 0, 0.5);
    transform: translateY(-1px);
    color: #ffffff !important;
}

.ck-share-link-btn {
    background: none;
    border: none;
    color: #64748b;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: color 0.2s ease;
}

.ck-share-link-btn:hover {
    color: #0f172a;
    text-decoration: underline;
}

/* Divider between top stage and editorial content */
.ck-section-divider {
    border-top: 1px solid #e2e8f0;
    width: 100%;
}

/* ==========================================
   EDITORIAL SECTION & 2-COLUMN LAYOUT
   ========================================== */
.ck-editorial-section {
    background: #ffffff;
    padding: 24px 0 60px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #334155;
}

.ck-detail-main-layout {
    display: flex;
    gap: 36px;
    align-items: flex-start;
    width: 100%;
}

/* Left column - main content starts from left (~78%) */
.ck-content-left-col {
    flex: 1 1 77%;
    min-width: 0;
    text-align: left;
}

/* Right column - sidebar for related offers (~23%) */
.ck-sidebar-right-col {
    flex: 0 0 23%;
    width: 23%;
    min-width: 270px;
    position: sticky;
    top: 90px;
    align-self: flex-start;
}

.ck-content-block {
    margin-bottom: 28px;
    text-align: left;
}

.ck-block-heading-with-icon {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.ck-emoji {
    font-size: 20px;
}

.ck-block-sub-heading {
    font-size: 16.5px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 10px;
}

.ck-bullet-list {
    list-style: none;
    padding-left: 0;
    margin-bottom: 0;
}

.ck-bullet-list li {
    position: relative;
    padding-left: 20px;
    font-size: 14px;
    line-height: 1.6;
    color: #334155;
    margin-bottom: 7px;
}

.ck-bullet-list li::before {
    content: "•";
    position: absolute;
    left: 4px;
    top: -1px;
    font-size: 18px;
    color: #64748b;
}

.ck-bullet-list li strong {
    color: #0f172a;
}

/* ==========================================
   RIGHT SIDEBAR STYLES
   ========================================== */
.ck-sidebar-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.ck-sidebar-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 14px 16px;
    border-bottom: 1px solid #e2e8f0;
}

.ck-sidebar-fire-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #fee2e2;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.ck-sidebar-title {
    font-size: 14.5px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.25;
}

.ck-sidebar-subtitle {
    font-size: 11px;
    color: #64748b;
    display: block;
}

.ck-sidebar-list {
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.ck-sidebar-item {
    background: #f8fafc;
    border: 1px solid #edf2f7;
    border-radius: 12px;
    padding: 12px;
    transition: all 0.2s ease;
}

.ck-sidebar-item:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    transform: translateY(-1px);
}

.ck-sidebar-item-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}

.ck-sidebar-store-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 700;
    color: #475569;
}

.ck-sidebar-store-img {
    width: 18px;
    height: 18px;
    object-fit: contain;
    border-radius: 4px;
}

.ck-sidebar-badge {
    display: inline-block;
    font-size: 10px;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 999px;
    background: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
    letter-spacing: 0.3px;
}

.ck-sidebar-item-title {
    font-size: 13px;
    font-weight: 600;
    line-height: 1.35;
    margin: 0;
}

.ck-sidebar-item-title a {
    color: #1e293b;
    text-decoration: none;
    transition: color 0.15s ease;
}

.ck-sidebar-item-title a:hover {
    color: #ea580c;
}

.ck-sidebar-uses {
    font-size: 11px;
    color: #94a3b8;
}

.ck-sidebar-grab-btn {
    display: inline-flex;
    align-items: center;
    font-size: 11.5px;
    font-weight: 700;
    color: #ea580c;
    text-decoration: none !important;
    padding: 3px 9px;
    border-radius: 6px;
    background: #fff7ed;
    border: 1px solid #fed7aa;
    transition: all 0.2s ease;
}

.ck-sidebar-grab-btn:hover {
    background: #ea580c;
    color: #ffffff !important;
}

.ck-sidebar-footer {
    padding: 10px 14px;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    text-align: center;
}

.ck-sidebar-all-link {
    font-size: 12.5px;
    font-weight: 700;
    color: #6366f1;
    text-decoration: none;
}

.ck-sidebar-all-link:hover {
    text-decoration: underline;
    color: #4f46e5;
}

/* Trust Badges Widget */
.ck-sidebar-trust-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    gap: 9px;
    font-size: 12px;
    font-weight: 600;
    color: #475569;
}

.ck-trust-item {
    display: flex;
    align-items: center;
}

/* Tables and Rich Description */
.ck-rich-description-body table {
    width: 100% !important;
    max-width: 100% !important;
    border-collapse: separate !important;
    border-spacing: 0 !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 10px !important;
    overflow: hidden !important;
    margin: 18px 0 !important;
    background: #ffffff !important;
}

.ck-rich-description-body th {
    background: #f8fafc !important;
    color: #1e293b !important;
    font-weight: 700 !important;
    padding: 10px 14px !important;
    font-size: 13.5px !important;
    border-bottom: 1px solid #e2e8f0 !important;
}

.ck-rich-description-body td {
    padding: 10px 14px !important;
    font-size: 13.5px !important;
    color: #334155 !important;
    border-bottom: 1px solid #f1f5f9 !important;
}

.ck-rich-description-body tr:last-child td {
    border-bottom: none !important;
}

/* Responsive Layout */
@media (max-width: 991.98px) {
    .ck-detail-main-layout {
        flex-direction: column;
        gap: 28px;
    }
    .ck-content-left-col {
        width: 100%;
    }
    .ck-sidebar-right-col {
        width: 100%;
        min-width: 100%;
        position: static;
    }
}

/* Hide broken local file images */
.ck-rich-description-body img[src^="file://"],
.ck-rich-description-body img[src*="AppData"],
.ck-rich-description-body img[src*="msohtmlclip"] {
    display: none !important;
}

@media (max-width: 576px) {
    .ck-amber-offer-card {
        padding: 18px 20px;
        height: auto;
        min-height: 260px;
    }
    .ck-card-main-title {
        font-size: 20px;
    }
    .ck-card-sub-info {
        font-size: 13px;
    }
    .ck-amber-card-right img {
        max-width: 120px;
        max-height: 170px;
    }
}

/* ==========================================
   SHARE MODAL STYLES (Modern YouTube / CashKaro Style)
   ========================================== */
.ck-share-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    animation: ckFadeIn 0.2s ease-out;
}

@keyframes ckFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.ck-share-modal-dialog {
    background: #ffffff;
    border-radius: 20px;
    width: 100%;
    max-width: 440px;
    box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.22);
    overflow: hidden;
    animation: ckModalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    border: 1px solid rgba(226, 232, 240, 0.8);
}

@keyframes ckModalPop {
    from {
        opacity: 0;
        transform: scale(0.94) translateY(10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.ck-share-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
}

.ck-share-modal-title {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
}

.ck-share-modal-close {
    background: #f1f5f9;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
}

.ck-share-modal-close:hover {
    background: #e2e8f0;
    color: #0f172a;
}

.ck-share-modal-body {
    padding: 20px 22px 24px;
}

.ck-share-copied-alert {
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
    padding: 9px 12px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
}

.ck-social-share-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 6px;
    padding: 2px 2px 4px;
    overflow-x: auto;
    scrollbar-width: none;
}

.ck-social-share-row::-webkit-scrollbar {
    display: none;
}

.ck-social-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 7px;
    text-decoration: none !important;
    flex: 1;
    min-width: 52px;
    transition: transform 0.2s ease;
}

.ck-social-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff !important;
    font-size: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.ck-social-item:hover .ck-social-circle {
    transform: translateY(-3px) scale(1.06);
    box-shadow: 0 7px 16px rgba(0, 0, 0, 0.18);
}

.ck-social-whatsapp .ck-social-circle { background: #25D366; }
.ck-social-facebook .ck-social-circle { background: #1877F2; }
.ck-social-twitter  .ck-social-circle { background: #000000; }
.ck-social-telegram .ck-social-circle { background: #229ED9; }
.ck-social-email    .ck-social-circle { background: #EA4335; }
.ck-social-linkedin .ck-social-circle { background: #0A66C2; }

.ck-social-label {
    font-size: 11px;
    font-weight: 600;
    color: #475569;
    text-align: center;
    white-space: nowrap;
}
</style>

{{-- Share Modal Popup (Modern YouTube / CashKaro Style) --}}
<div id="ckShareModal" class="ck-share-modal-backdrop" style="display: none;" onclick="closeShareModal(event)">
    <div class="ck-share-modal-dialog" onclick="event.stopPropagation()">
        {{-- Header --}}
        <div class="ck-share-modal-header">
            <h5 class="ck-share-modal-title mb-0">@lang('Share')</h5>
            <button type="button" class="ck-share-modal-close" onclick="closeShareModal()" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="ck-share-modal-body">
            {{-- Copied Toast Notification --}}
            <div class="ck-share-copied-alert" id="ckShareCopiedAlert">
                <i class="fas fa-check-circle me-1"></i> @lang('Link copied! Share it directly with friends:')
            </div>

            {{-- Circular Social Share Row --}}
            <div class="ck-social-share-row">
                {{-- WhatsApp --}}
                <a href="https://web.whatsapp.com/send?text={{ rawurlencode($coupon->title . ' - ' . $finalTargetLink) }}" id="ckShareWhatsapp" target="_blank" class="ck-social-item ck-social-whatsapp">
                    <div class="ck-social-circle"><i class="fab fa-whatsapp"></i></div>
                    <span class="ck-social-label">WhatsApp</span>
                </a>

                {{-- Facebook --}}
                <a href="#" id="ckShareFacebook" target="_blank" class="ck-social-item ck-social-facebook">
                    <div class="ck-social-circle"><i class="fab fa-facebook-f"></i></div>
                    <span class="ck-social-label">Facebook</span>
                </a>

                {{-- X / Twitter --}}
                <a href="#" id="ckShareTwitter" target="_blank" class="ck-social-item ck-social-twitter">
                    <div class="ck-social-circle"><i class="fab fa-x-twitter"></i></div>
                    <span class="ck-social-label">X</span>
                </a>

                {{-- Telegram --}}
                <a href="#" id="ckShareTelegram" target="_blank" class="ck-social-item ck-social-telegram">
                    <div class="ck-social-circle"><i class="fab fa-telegram-plane"></i></div>
                    <span class="ck-social-label">Telegram</span>
                </a>

                {{-- Email --}}
                <a href="#" id="ckShareEmail" target="_blank" class="ck-social-item ck-social-email">
                    <div class="ck-social-circle"><i class="fas fa-envelope"></i></div>
                    <span class="ck-social-label">Email</span>
                </a>

                {{-- LinkedIn --}}
                <a href="#" id="ckShareLinkedin" target="_blank" class="ck-social-item ck-social-linkedin">
                    <div class="ck-social-circle"><i class="fab fa-linkedin-in"></i></div>
                    <span class="ck-social-label">LinkedIn</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     SCRIPTS
     ========================================== -->
@push('script')
<script>
    // Copy Coupon Code
    function copyCouponCode(code) {
        if (!code) return;
        
        navigator.clipboard.writeText(code).then(function() {
            var label = document.getElementById('copyCodeBtnLabel');
            if (label) {
                var prevText = label.innerText;
                label.innerText = 'Copied!';
                label.style.color = '#10b981';
                setTimeout(function() {
                    label.innerText = prevText;
                    label.style.color = '';
                }, 2500);
            }
            if (typeof Toast !== 'undefined') {
                Toast.fire({
                    icon: 'success',
                    title: 'Coupon Code Copied: ' + code
                });
            }
        }).catch(function() {
            prompt("Copy code manually:", code);
        });
    }

    // Robust Clipboard Copy Function with Fallback
    function safeCopyText(text, onSuccess, onFail) {
        if (!text) return;
        if (navigator.clipboard && window.isSecureContext && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                if (onSuccess) onSuccess();
            }).catch(function() {
                fallbackCopy(text, onSuccess, onFail);
            });
        } else {
            fallbackCopy(text, onSuccess, onFail);
        }
    }

    function fallbackCopy(text, onSuccess, onFail) {
        try {
            var textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-9999px";
            textArea.style.top = "-9999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            var successful = document.execCommand('copy');
            document.body.removeChild(textArea);
            if (successful && onSuccess) onSuccess();
            else if (!successful && onFail) onFail();
        } catch (err) {
            if (onFail) onFail();
        }
    }

    // Open Share Modal & Automatically Copy Link to Clipboard
    function openShareModal(link, title, btn) {
        if (!link) {
            link = window.location.href;
        }

        // 1. Automatically copy to clipboard safely with visual confirmation
        safeCopyText(link, function() {
            if (btn) {
                var orig = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check text-success me-1"></i> Link Copied!';
                setTimeout(function() {
                    btn.innerHTML = orig;
                }, 3000);
            }
        });

        // 2. On Mobile Devices with Native Share API, open phone's native share sheet (WhatsApp, Gmail, etc.)
        var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        if (isMobile && navigator.share) {
            navigator.share({
                title: title || document.title,
                text: (title ? title + ' - ' : '') + 'Check out this deal on CouponsEfra!',
                url: link
            }).catch(function(err) {
                if (err.name !== 'AbortError') {
                    showShareDialog(link, title);
                }
            });
            return;
        }

        // 3. On Desktop (or fallback): Open sleek YouTube-style Share Dialog
        showShareDialog(link, title);
    }

    function showShareDialog(link, title) {
        var encodedLink = encodeURIComponent(link);
        var encodedTitle = encodeURIComponent(title || document.title);
        var fullMsg = encodeURIComponent((title ? title + " - " : "") + link);

        var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        var wa = document.getElementById('ckShareWhatsapp');
        if (wa) {
            wa.href = isMobile 
                ? 'https://api.whatsapp.com/send?text=' + fullMsg 
                : 'https://web.whatsapp.com/send?text=' + fullMsg;
        }

        var fb = document.getElementById('ckShareFacebook');
        if (fb) fb.href = 'https://www.facebook.com/sharer/sharer.php?u=' + encodedLink;

        var tw = document.getElementById('ckShareTwitter');
        if (tw) tw.href = 'https://twitter.com/intent/tweet?text=' + encodedTitle + '&url=' + encodedLink;

        var tg = document.getElementById('ckShareTelegram');
        if (tg) tg.href = 'https://t.me/share/url?url=' + encodedLink + '&text=' + encodedTitle;

        var mail = document.getElementById('ckShareEmail');
        if (mail) mail.href = 'mailto:?subject=' + encodedTitle + '&body=' + encodeURIComponent('Check out this amazing deal: ') + encodedLink;

        var li = document.getElementById('ckShareLinkedin');
        if (li) li.href = 'https://www.linkedin.com/sharing/share-offsite/?url=' + encodedLink;

        var modal = document.getElementById('ckShareModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    // Close Share Modal
    function closeShareModal(e) {
        if (e && e.target && e.target !== document.getElementById('ckShareModal') && !e.target.closest('.ck-share-modal-close')) {
            return;
        }
        var modal = document.getElementById('ckShareModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    // Close on Escape Key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeShareModal();
        }
    });

    // Copy Button inside Share Modal
    function copyShareInputLink() {
        var input = document.getElementById('ckShareLinkInput');
        if (!input) return;
        input.select();
        safeCopyText(input.value, function() {
            var btnText = document.getElementById('ckModalCopyBtnText');
            if (btnText) {
                btnText.innerText = 'Copied! ✓';
                setTimeout(function() {
                    btnText.innerText = 'Copy';
                }, 2500);
            }
        });
    }

    // Backwards-compatible alias for copyShareableLink
    function copyShareableLink(link, btn) {
        openShareModal(link, '{{ addslashes($coupon->title) }}', btn);
    }

    // Optional click tracking callback
    function trackClickCount() {
        console.log('Grab Deal initiated for coupon {{ $coupon->id }}');
    }

    // Live Rolling Countdown Timer
    (function initDetailCountdown() {
        var timerEl = document.getElementById('ckDetailCountdown');
        if (!timerEl) return;

        var target = new Date();
        target.setHours(target.getHours() + 1);
        target.setMinutes(target.getMinutes() + 25);
        target.setSeconds(target.getSeconds() + 55);

        function updateTimer() {
            var now = new Date();
            var diff = Math.max(0, Math.floor((target - now) / 1000));
            var hours = Math.floor(diff / 3600);
            var minutes = Math.floor((diff % 3600) / 60);
            var seconds = diff % 60;

            var hStr = hours < 10 ? '0' + hours : hours;
            var mStr = minutes < 10 ? '0' + minutes : minutes;
            var sStr = seconds < 10 ? '0' + seconds : seconds;

            timerEl.textContent = hStr + ':' + mStr + ':' + sStr;
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    })();
</script>
@endpush

@endsection
