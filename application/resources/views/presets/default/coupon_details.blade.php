@extends($activeTemplate.'layouts.frontend')
@section('content')

@php
    $finalTargetLink = appendTrackingSource($coupon->link);
@endphp

<!-- ==========================================
     CASHKARO STYLE FLASH DEAL HERO SECTION
     ========================================== -->
<section class="ck-detail-hero-section">
    <div class="ck-hero-floral-bg"></div>
    <div class="container position-relative" style="z-index: 2;">
        
        {{-- Back Navigation --}}
        <div class="mb-3 text-start">
            <a href="{{ url()->previous() == url()->current() ? route('home') : url()->previous() }}" class="ck-back-link">
                <i class="fas fa-arrow-left me-1"></i> @lang('Back to Deals')
            </a>
        </div>

        {{-- Top Header: FLASH DEAL & Countdown Timer --}}
        <div class="ck-hero-header text-center">
            <h1 class="ck-flash-deal-badge">@lang('FLASH DEAL')</h1>
            <div class="ck-detail-timer-pill">
                <i class="far fa-clock"></i> @lang('Ends in') <span id="ckDetailCountdown">01:25:55</span>
            </div>
        </div>

        {{-- Center Stage: Floating Ice-Blue Deal Card + Code Box + Grab Deal Button --}}
        <div class="ck-hero-card-container">
            {{-- Ice-Blue Card --}}
            <div class="ck-hero-ice-card">
                <div class="ck-card-left-content">
                    {{-- Store Logo Pill --}}
                    <div class="ck-store-logo-pill">
                        @if($coupon->store && $coupon->store->image)
                            <img src="{{ getImage(getFilePath('store').'/'.$coupon->store->image) }}" alt="{{ $coupon->store->name }}">
                        @elseif($coupon->store)
                            <span class="ck-store-text">{{ Str::limit($coupon->store->name, 14) }}</span>
                        @else
                            <span class="ck-store-text">@lang('EXCLUSIVE')</span>
                        @endif
                    </div>

                    {{-- Title & Short Subtitle --}}
                    <h2 class="ck-card-main-title">{{ $coupon->title }}</h2>
                    <p class="ck-card-sub-info">
                        {{ Str::limit(html_entity_decode(strip_tags($coupon->description)), 55) }}
                    </p>

                    {{-- Cashback Badge --}}
                    <div class="ck-card-cashback-badge">
                        @if($coupon->is_cashback == 1)
                            Flat {{ $coupon->cashback_amount }}% Cashback
                        @else
                            Verified Direct Deal
                        @endif
                    </div>
                </div>

                {{-- Product / Deal Cutout Image on Right --}}
                <div class="ck-card-right-visual">
                    @if($coupon->image)
                        <img src="{{ getImage(getFilePath('coupon').'/'.$coupon->image) }}" alt="{{ $coupon->title }}">
                    @elseif($coupon->store && $coupon->store->image)
                        <img src="{{ getImage(getFilePath('store').'/'.$coupon->store->image) }}" alt="{{ $coupon->store->name }}">
                    @endif
                </div>
            </div>

            {{-- Coupon Code Box (Below Card) --}}
            <div class="ck-hero-code-box">
                <span class="ck-use-code-text">@lang('Use Code')</span>
                
                @if($coupon->code)
                    <div class="ck-dashed-code-pill" id="couponCodeDisplay">{{ $coupon->code }}</div>
                    <button type="button" class="ck-copy-code-action" onclick="copyCouponCode('{{ $coupon->code }}')">
                        <span id="copyCodeBtnLabel">@lang('Copy Code')</span>
                        <i class="far fa-copy ms-1"></i>
                    </button>
                @else
                    <div class="ck-dashed-code-pill no-code">@lang('No Code Needed')</div>
                    <span class="ck-auto-applied-tag">
                        <i class="fas fa-check-circle me-1"></i> @lang('Auto Applied')
                    </span>
                @endif
            </div>

            {{-- Big Vibrant Orange Grab Deal Button --}}
            <a href="{{ route('coupon.redirect', $coupon->id) }}" target="_blank" class="ck-hero-grab-deal-btn" onclick="trackClickCount()">
                @lang('Grab Deal') <span class="ms-1">&rarr;</span>
            </a>
            
            <div class="text-center mt-2">
                <button type="button" class="ck-share-link-btn" onclick="copyShareableLink('{{ $finalTargetLink }}', this)">
                    <i class="fas fa-link me-1"></i> @lang('Copy Shareable Link')
                </button>
            </div>
        </div>
    </div>
</section>

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
                                    {{ @$coupon->store->name ?? 'Store' }} @lang('Offers')
                                </h5>
                                <span class="ck-sidebar-subtitle">@lang('Active & related deals')</span>
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
                                        <span>{{ Str::limit($rel->store->name ?? 'Store', 12) }}</span>
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

                    @if($coupon->store_id)
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
/* CashKaro Deal Hero Section */
.ck-detail-hero-section {
    position: relative;
    background: radial-gradient(circle at 50% 15%, #6b1473 0%, #4a044e 45%, #2a0845 100%);
    padding: 35px 0 55px;
    overflow: hidden;
    color: #ffffff;
}

.ck-hero-floral-bg {
    position: absolute;
    inset: 0;
    background-image: 
        radial-gradient(circle at 20% 40%, rgba(255, 255, 255, 0.05) 0%, transparent 40%),
        radial-gradient(circle at 80% 60%, rgba(255, 255, 255, 0.05) 0%, transparent 40%),
        radial-gradient(circle at 50% 80%, rgba(217, 70, 239, 0.12) 0%, transparent 60%);
    pointer-events: none;
    z-index: 1;
}

.ck-back-link {
    display: inline-flex;
    align-items: center;
    color: rgba(255, 255, 255, 0.85) !important;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none !important;
    background: rgba(255, 255, 255, 0.12);
    padding: 5px 14px;
    border-radius: 20px;
    transition: all 0.2s ease;
}

.ck-back-link:hover {
    color: #ffffff !important;
    background: rgba(255, 255, 255, 0.22);
}

.ck-hero-header {
    margin-bottom: 22px;
}

.ck-flash-deal-badge {
    font-size: 28px;
    font-weight: 900;
    color: #ffffff !important;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 10px;
    text-shadow: 0 4px 14px rgba(0, 0, 0, 0.35);
}

.ck-detail-timer-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #ffffff;
    color: #0f172a;
    font-size: 13px;
    font-weight: 700;
    padding: 5px 18px;
    border-radius: 30px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.18);
}

.ck-detail-timer-pill i {
    color: #e11d48;
}

/* Floating Ice-Blue Deal Card */
.ck-hero-card-container {
    max-width: 490px;
    margin: 0 auto;
}

.ck-hero-ice-card {
    background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 50%, #f0fdfa 100%);
    border-radius: 22px;
    padding: 24px 26px;
    position: relative;
    overflow: hidden;
    min-height: 220px;
    display: flex;
    justify-content: space-between;
    box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.85);
}

.ck-card-left-content {
    flex: 1 1 58%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-start;
    padding-right: 8px;
    z-index: 2;
}

.ck-store-logo-pill {
    padding: 6px 14px;
    border-radius: 10px;
    height: 40px;
    width: fit-content;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 14px;
}

.ck-store-logo-pill img {
    max-height: 24px;
    max-width: 85px;
    object-fit: contain;
}

.ck-store-text {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    text-transform: uppercase;
}

.ck-card-main-title {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a !important;
    line-height: 1.25;
    margin: 0 0 4px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.ck-card-sub-info {
    font-size: 13px;
    color: #475569;
    font-weight: 500;
    line-height: 1.35;
    margin: 0 0 14px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.ck-card-cashback-badge {
    font-size: 17px;
    font-weight: 900;
    color: #0f172a;
    margin-top: auto;
}

.ck-card-right-visual {
    flex: 0 0 42%;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    position: relative;
    z-index: 1;
}

.ck-card-right-visual img {
    max-width: 150px;
    max-height: 165px;
    object-fit: contain;
    filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.15));
}

/* Coupon Code Bar */
.ck-hero-code-box {
    background: #ffffff;
    border-radius: 14px;
    padding: 12px 20px;
    margin-top: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.ck-use-code-text {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
}

.ck-dashed-code-pill {
    border: 1.5px dashed #2563eb;
    background: #eff6ff;
    color: #1d4ed8;
    font-size: 15px;
    font-weight: 800;
    padding: 5px 16px;
    border-radius: 8px;
    letter-spacing: 1px;
    user-select: all;
}

.ck-dashed-code-pill.no-code {
    border-color: #10b981;
    background: #ecfdf5;
    color: #047857;
    font-size: 13px;
}

.ck-copy-code-action {
    background: none;
    border: none;
    color: #2563eb;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    padding: 4px 6px;
    transition: color 0.2s ease;
}

.ck-copy-code-action:hover {
    color: #1d4ed8;
}

.ck-auto-applied-tag {
    color: #059669;
    font-size: 13px;
    font-weight: 700;
}

/* Vibrant Orange Grab Deal Button */
.ck-hero-grab-deal-btn {
    background: linear-gradient(135deg, #ff6b00 0%, #ea580c 100%);
    color: #ffffff !important;
    font-size: 17px;
    font-weight: 800;
    text-align: center;
    padding: 15px 24px;
    border-radius: 14px;
    display: block;
    width: 100%;
    margin-top: 14px;
    text-decoration: none !important;
    box-shadow: 0 8px 24px rgba(234, 88, 12, 0.45);
    transition: all 0.25s ease;
    letter-spacing: 0.3px;
}

.ck-hero-grab-deal-btn:hover {
    background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
    box-shadow: 0 12px 30px rgba(234, 88, 12, 0.6);
    transform: translateY(-2px);
    color: #ffffff !important;
}

.ck-share-link-btn {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.75);
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    transition: color 0.2s ease;
}

.ck-share-link-btn:hover {
    color: #ffffff;
    text-decoration: underline;
}

/* ==========================================
   EDITORIAL SECTION & 2-COLUMN LAYOUT
   ========================================== */
.ck-editorial-section {
    background: #ffffff;
    padding: 40px 0 70px;
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
    .ck-flash-deal-badge {
        font-size: 24px;
    }
    .ck-hero-ice-card {
        padding: 18px 18px;
        min-height: 200px;
    }
    .ck-card-main-title {
        font-size: 19px;
    }
    .ck-card-right-visual img {
        max-width: 120px;
        max-height: 135px;
    }
}
</style>

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

    // Copy Shareable Link
    function copyShareableLink(link, btn) {
        if (!link) return;
        navigator.clipboard.writeText(link).then(function() {
            var orig = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check text-success me-1"></i> Link Copied!';
            setTimeout(function() {
                btn.innerHTML = orig;
            }, 2500);
        });
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
