@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
    $secondAd = App\Models\Ad::skip(1)->first();
    // Estimate read time based on word count
    $wordCount = str_word_count(strip_tags($blog->data_values->description ?? ''));
    $readMinutes = max(1, ceil($wordCount / 200));
@endphp

<!-- ==================== Modern Blog Details Section ==================== -->
<section class="blog-details-section py-5">
    <div class="container-fluid container-custom">
        <!-- Breadcrumb / Header Top -->
        <div class="blog-breadcrumb-row mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog') }}">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit(__($blog->data_values->title), 35) }}</li>
                </ol>
            </nav>
        </div>

        <div class="row gy-5">
            <!-- Main Article Column -->
            <div class="col-lg-8">
                <article class="blog-main-card">
                    <!-- Article Editorial Header -->
                    <div class="article-header">
                        <div class="category-pill-wrap mb-3">
                            <span class="editorial-badge"><i class="fas fa-bolt"></i> Buying Guide & Deals</span>
                            <span class="read-time-badge"><i class="far fa-clock"></i> {{ $readMinutes }} Min Read</span>
                        </div>
                        
                        <h1 class="article-title">{{ __($blog->data_values->title) }}</h1>

                        <!-- Author & Meta Info Row -->
                        <div class="article-meta-bar">
                            <div class="author-info">
                                <div class="author-avatar">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <div class="author-details">
                                    <span class="author-name">CouponsEfra Editorial Team</span>
                                    <span class="article-date"><i class="far fa-calendar-alt"></i> {{ showDateTime($blog->created_at, 'M d, Y') }}</span>
                                </div>
                            </div>

                            <div class="meta-badges-right">
                                <span class="verified-pill"><i class="fas fa-check-circle"></i> Verified Buying Guide</span>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Hero Thumbnail -->
                    @if($blog->data_values->blog_image)
                    <div class="article-hero-thumb">
                        <img src="{{ getImage(getFilePath('blog').'/'. $blog->data_values->blog_image) }}" alt="{{ __($blog->data_values->title) }}">
                    </div>
                    @endif

                    <!-- Article Body Content -->
                    <div class="article-rich-body" id="articleRichBody">
                        {!! $blog->data_values->description !!}
                    </div>

                    <!-- Article Footer / Share Bar -->
                    <div class="article-share-footer">
                        <div class="share-label-wrap">
                            <span class="share-title"><i class="fas fa-share-alt"></i> Share this Guide:</span>
                        </div>
                        <div class="share-buttons-group">
                            <a class="share-btn share-whatsapp" target="_blank" href="https://api.whatsapp.com/send?text={{ urlencode(__($blog->data_values->title) . ' - ' . Request::url()) }}" title="Share on WhatsApp">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                            <a class="share-btn share-fb" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::url()) }}" title="Share on Facebook">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a class="share-btn share-twitter" target="_blank" href="https://twitter.com/intent/tweet?url={{ urlencode(Request::url()) }}&text={{ urlencode(__($blog->data_values->title)) }}" title="Share on X">
                                <i class="fab fa-twitter"></i> X (Twitter)
                            </a>
                            <a class="share-btn share-linkedin" target="_blank" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(Request::url()) }}" title="Share on LinkedIn">
                                <i class="fab fa-linkedin-in"></i> LinkedIn
                            </a>
                            <button class="share-btn share-copy" onclick="copyArticleUrl(this)" title="Copy Link">
                                <i class="fas fa-link"></i> <span class="copy-text">Copy Link</span>
                            </button>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                <aside class="blog-sidebar-sticky">
                    <!-- Exclusive Coupon Alert Banner -->
                    <div class="sidebar-promo-card mb-4">
                        <div class="promo-card-icon"><i class="fas fa-tags"></i></div>
                        <h5>Looking for Smart Savings?</h5>
                        <p>Browse 1000+ verified coupons, cashback rates, and special brand discount codes.</p>
                        <a href="{{ route('coupon') }}" class="btn-promo-cta"><i class="fas fa-fire"></i> Explore Hot Coupons</a>
                    </div>

                    <!-- Sidebar Advertisement -->
                    @if($secondAd)
                    <div class="sidebar-ad-card mb-4">
                        <span class="ad-label">Advertisement</span>
                        <a href="{{ @$secondAd->link }}" target="_blank" class="ad-thumb-link">
                            <img src="{{ getImage(getFilePath('adImage') . '/' . @$secondAd->image) }}" alt="Special Offer">
                        </a>
                    </div>
                    @endif

                    <!-- Trending / Latest Articles -->
                    <div class="sidebar-widget-card">
                        <div class="widget-header">
                            <h5 class="widget-title"><i class="fas fa-newspaper"></i> Latest Articles</h5>
                        </div>
                        <div class="latest-articles-list">
                            @foreach ($latests as $item)
                            <a href="{{ route('blog.details', ['slug' => slug($item->data_values->title), 'id' => $item->id]) }}" class="latest-article-row">
                                <div class="latest-thumb-wrap">
                                    <img src="{{ getImage(getFilePath('blog').'/'. $item->data_values->blog_image) }}" alt="{{ $item->data_values->title }}">
                                </div>
                                <div class="latest-content-wrap">
                                    <h6 class="latest-row-title">{{ Str::limit(__($item->data_values->title), 52) }}</h6>
                                    <span class="latest-row-date"><i class="far fa-clock"></i> {{ showDateTime($item->created_at, 'M d, Y') }}</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection

@push('style')
<style>
    /* ========================================================
       MODERN BLOG DETAILS SYSTEM & EDITORIAL STYLING
       ======================================================== */
    body {
        background-color: #f8fafc;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Breadcrumbs */
    .blog-breadcrumb-row .breadcrumb {
        background: transparent;
        padding: 0;
        font-size: 13.5px;
        font-weight: 500;
    }
    .blog-breadcrumb-row .breadcrumb a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.2s;
    }
    .blog-breadcrumb-row .breadcrumb a:hover {
        color: #2563eb;
    }
    .blog-breadcrumb-row .breadcrumb-item.active {
        color: #0f172a;
        font-weight: 600;
    }

    /* Main Article Container */
    .blog-main-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 36px;
        overflow: hidden;
    }

    /* Editorial Header */
    .article-header {
        margin-bottom: 24px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 22px;
    }
    .category-pill-wrap {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    .editorial-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #2563eb;
        font-size: 12px;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .read-time-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
    }
    .article-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.35;
        margin: 12px 0 16px;
        letter-spacing: -0.3px;
    }
    .article-meta-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
    }
    .author-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .author-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(37, 99, 235, 0.2);
    }
    .author-name {
        display: block;
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
    }
    .article-date {
        display: block;
        font-size: 12px;
        color: #64748b;
        margin-top: 1px;
    }
    .verified-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ecfdf5;
        color: #059669;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        border: 1px solid #a7f3d0;
    }

    /* Hero Thumbnail */
    .article-hero-thumb {
        max-width: 480px;
        max-height: 280px;
        width: 100%;
        margin: 0 auto 28px;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .article-hero-thumb img {
        max-width: 100%;
        max-height: 280px;
        width: auto;
        height: auto;
        object-fit: contain;
        display: block;
        margin: 0 auto;
    }

    /* ========================================================
       ARTICLE RICH BODY TYPOGRAPHY & COMPONENTS
       ======================================================== */
    .article-rich-body {
        font-size: 15.5px;
        line-height: 1.8;
        color: #334155;
    }
    .article-lead {
        font-size: 17px;
        line-height: 1.75;
        color: #1e293b;
        font-weight: 500;
        margin-bottom: 22px;
    }
    .article-rich-body h2,
    .article-section-title {
        font-size: 21px;
        font-weight: 800;
        color: #0f172a;
        margin: 36px 0 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-left: 4px solid #2563eb;
        padding-left: 14px;
        line-height: 1.35;
    }
    .article-rich-body h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin: 26px 0 12px;
    }
    .article-rich-body h4 {
        font-size: 16px;
        font-weight: 700;
        color: #334155;
        margin: 20px 0 10px;
    }
    .article-rich-body p {
        margin-bottom: 16px;
    }
    .section-desc {
        font-size: 14.5px;
        color: #64748b;
        margin-top: -6px;
        margin-bottom: 20px;
    }
    .article-rich-body ul {
        list-style: none;
        padding-left: 0;
        margin: 16px 0 22px;
    }
    .article-rich-body ul li {
        position: relative;
        padding-left: 26px;
        margin-bottom: 10px;
        font-size: 14.5px;
        line-height: 1.6;
        color: #334155;
    }
    .article-rich-body ul li::before {
        content: "\f058";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        left: 0;
        top: 3px;
        color: #10b981;
        font-size: 14px;
    }
    .article-rich-body ol {
        padding-left: 22px;
        margin: 16px 0 22px;
    }
    .article-rich-body ol li {
        margin-bottom: 10px;
        font-size: 14.5px;
        padding-left: 4px;
    }
    .article-rich-body a {
        color: #2563eb;
        text-decoration: underline;
        font-weight: 600;
    }

    /* Contain standard images */
    .article-rich-body img {
        max-width: 440px;
        max-height: 280px;
        width: auto;
        height: auto;
        border-radius: 12px;
        display: block;
        margin: 16px auto;
        object-fit: contain;
    }

    /* Callout & Guide Box */
    .article-callout-box {
        display: flex;
        gap: 16px;
        background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
        border: 1px solid #bfdbfe;
        border-radius: 14px;
        padding: 20px 22px;
        margin: 24px 0 30px;
        align-items: flex-start;
    }
    .callout-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #2563eb;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .callout-body h6 {
        font-size: 15px;
        font-weight: 800;
        color: #1e3a8a;
        margin: 0 0 10px;
    }
    .overview-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .overview-chips span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #334155;
    }
    .overview-chips span i {
        color: #10b981;
    }

    /* ========================================================
       PRODUCT REVIEW CARDS (Apple & Noise)
       ======================================================== */
    .product-cards-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin: 22px 0 36px;
    }
    .product-review-card {
        display: flex;
        gap: 24px;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        align-items: center;
        transition: all 0.25s ease;
    }
    .product-review-card:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.09);
    }
    .product-thumb-wrap {
        width: 100px;
        height: 100px;
        background: #f8fafc;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        flex-shrink: 0;
        border: 1px solid #edf2f7;
        padding: 8px;
    }
    .product-thumb-wrap img {
        max-width: 86% !important;
        max-height: 86% !important;
        width: auto !important;
        height: auto !important;
        object-fit: contain !important;
        display: block;
        margin: 0 auto !important;
        transition: transform 0.25s ease;
    }
    .product-review-card:hover .product-thumb-wrap img {
        transform: scale(1.05);
    }
    .product-rank-badge {
        position: absolute;
        top: -9px;
        left: 8px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 10px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .product-rank-badge.standard {
        background: linear-gradient(135deg, #475569, #334155);
    }
    .product-rank-badge.noise {
        background: linear-gradient(135deg, #059669, #047857);
    }

    .product-info-wrap {
        flex: 1;
    }
    .product-header-line {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 8px;
    }
    .product-name {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }
    .product-meta-badges {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .badge-price {
        background: #eff6ff;
        color: #2563eb;
        font-weight: 800;
        font-size: 14.5px;
        padding: 3px 10px;
        border-radius: 6px;
        border: 1px solid #bfdbfe;
    }
    .badge-rating {
        background: #fefce8;
        color: #ca8a04;
        font-weight: 700;
        font-size: 12.5px;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .product-summary {
        font-size: 13.5px;
        color: #64748b;
        line-height: 1.55;
        margin-bottom: 12px;
    }
    .product-specs-list {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 14px;
    }
    .product-specs-list span {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f8fafc;
        padding: 3px 8px;
        border-radius: 6px;
        border: 1px solid #edf2f7;
    }
    .product-action-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .btn-check-deal {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff !important;
        font-size: 12.5px;
        font-weight: 700;
        padding: 7px 16px;
        border-radius: 25px;
        text-decoration: none !important;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
    }
    .btn-check-deal:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .btn-check-deal.btn-noise {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        box-shadow: 0 2px 6px rgba(5, 150, 105, 0.2);
    }
    .btn-check-deal.btn-noise:hover {
        background: linear-gradient(135deg, #047857 0%, #065f46 100%);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }

    /* ========================================================
       COMPARISON TABLE
       ======================================================== */
    .table-responsive-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin: 22px 0 34px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }
    .styled-comparison-table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        margin: 0 !important;
    }
    .styled-comparison-table th {
        background: #1e293b;
        color: #ffffff;
        padding: 14px 16px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        text-align: left;
    }
    .styled-comparison-table td {
        padding: 13px 16px;
        font-size: 13.5px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .styled-comparison-table tbody tr:hover {
        background: #f8fafc;
    }
    .table-pill {
        display: inline-block;
        font-weight: 800;
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .table-pill.primary {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }
    .table-pill.success {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    /* ========================================================
       ANALYSIS CARDS & CALLOUTS
       ======================================================== */
    .analysis-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }
    .analysis-card-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .analysis-points {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 14px;
        margin-bottom: 14px;
    }
    .brand-item {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        padding: 14px;
        border-radius: 10px;
    }
    .brand-item p {
        margin: 6px 0 0;
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
    }
    .brand-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .brand-badge.apple {
        background: #000000;
        color: #ffffff;
    }
    .brand-badge.noise {
        background: #2563eb;
        color: #ffffff;
    }
    .analysis-text {
        font-size: 14.5px;
        color: #475569;
        line-height: 1.65;
        margin: 0;
    }
    .takeaway-pill {
        margin-top: 14px;
        background: #eff6ff;
        border-left: 3px solid #2563eb;
        padding: 10px 14px;
        border-radius: 6px;
        font-size: 13.5px;
        color: #1e3a8a;
    }

    /* Battery Comparison Grid */
    .battery-comparison-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 14px;
    }
    .battery-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px;
        display: flex;
        flex-direction: column;
    }
    .battery-box.highlight {
        background: #ecfdf5;
        border-color: #a7f3d0;
    }
    .battery-label {
        font-size: 12.5px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .battery-stat {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin: 2px 0 6px;
    }
    .battery-box.highlight .battery-stat {
        color: #059669;
    }
    .battery-note {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.45;
    }

    /* Cashback Action Banner */
    .cashback-action-banner {
        display: flex;
        gap: 20px;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #ffffff;
        border-radius: 16px;
        padding: 26px 28px;
        margin: 32px 0;
        align-items: center;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.15);
    }
    .banner-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .banner-body h4 {
        color: #ffffff;
        font-size: 18px;
        font-weight: 800;
        margin: 0 0 6px;
    }
    .banner-body p {
        color: #94a3b8;
        font-size: 13.5px;
        line-height: 1.55;
        margin-bottom: 14px;
    }
    .banner-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn-banner-action {
        background: #10b981;
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 700;
        padding: 8px 18px;
        border-radius: 25px;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-banner-action:hover {
        background: #059669;
        transform: translateY(-2px);
    }
    .btn-banner-outline {
        background: transparent;
        color: #ffffff !important;
        border: 1.5px solid #475569;
        font-size: 13px;
        font-weight: 700;
        padding: 8px 18px;
        border-radius: 25px;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-banner-outline:hover {
        border-color: #94a3b8;
        background: rgba(255, 255, 255, 0.05);
    }

    /* Decision Checklist Cards */
    .decision-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
        margin-bottom: 30px;
    }
    .decision-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }
    .decision-card.apple-choice {
        border-top: 4px solid #000000;
    }
    .decision-card.noise-choice {
        border-top: 4px solid #2563eb;
    }
    .decision-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 14px;
    }
    .decision-badge.noise {
        color: #2563eb;
    }
    .decision-card ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .decision-card ul li {
        font-size: 13.5px;
        color: #334155;
        position: relative;
        padding-left: 22px;
        margin-bottom: 8px;
        line-height: 1.5;
    }
    .decision-card ul li::before {
        content: "\f00c";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        left: 0;
        top: 2px;
        color: #10b981;
        font-size: 12px;
    }

    /* Verdict Grid */
    .verdict-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        margin-bottom: 34px;
    }
    .verdict-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px;
        display: flex;
        flex-direction: column;
    }
    .verdict-category {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .verdict-model {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
    }

    /* Explore Product Grid */
    .explore-product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 14px;
        margin: 20px 0 34px;
    }
    .explore-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }
    .explore-item-card:hover {
        border-color: #3b82f6;
        transform: translateY(-3px);
        box-shadow: 0 8px 18px rgba(59, 130, 246, 0.08);
    }
    .explore-img-wrap {
        width: 72px;
        height: 72px;
        background: #f8fafc;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6px;
        margin-bottom: 10px;
        border: 1px solid #edf2f7;
    }
    .explore-img-wrap img {
        max-width: 86% !important;
        max-height: 86% !important;
        width: auto !important;
        height: auto !important;
        object-fit: contain !important;
        display: block;
        margin: 0 auto !important;
    }
    .explore-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px;
    }
    .explore-tag {
        font-size: 11px;
        color: #64748b;
        margin-bottom: 12px;
        line-height: 1.35;
    }
    .explore-btn {
        margin-top: auto;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f1f5f9;
        color: #2563eb !important;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        text-decoration: none !important;
        transition: all 0.2s;
    }
    .explore-btn:hover {
        background: #2563eb;
        color: #ffffff !important;
    }

    /* Tips Box */
    .article-tips-box {
        background: #fefce8;
        border: 1px solid #fef08a;
        border-radius: 12px;
        padding: 20px 22px;
        margin-top: 26px;
    }
    .article-tips-box h4 {
        font-size: 16px;
        font-weight: 800;
        color: #854d0e;
        margin: 0 0 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .article-tips-box ul {
        margin: 0;
        padding-left: 0;
    }
    .article-tips-box ul li {
        font-size: 13.5px;
        color: #713f12;
        margin-bottom: 8px;
    }

    /* Article Share Footer */
    .article-share-footer {
        margin-top: 40px;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
    }
    .share-title {
        font-size: 14.5px;
        font-weight: 800;
        color: #0f172a;
    }
    .share-buttons-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .share-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none !important;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        cursor: pointer;
    }
    .share-whatsapp { background: #25d366; color: #fff !important; }
    .share-fb { background: #1877f2; color: #fff !important; }
    .share-twitter { background: #000000; color: #fff !important; }
    .share-linkedin { background: #0a66c2; color: #fff !important; }
    .share-copy { background: #ffffff; color: #334155 !important; border-color: #cbd5e1; }
    .share-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    /* ========================================================
       SIDEBAR
       ======================================================== */
    .blog-sidebar-sticky {
        position: sticky;
        top: 90px;
    }
    .sidebar-promo-card {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        border-radius: 14px;
        padding: 24px 20px;
        text-align: center;
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.18);
    }
    .promo-card-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin: 0 auto 12px;
    }
    .sidebar-promo-card h5 {
        color: #ffffff;
        font-size: 16px;
        font-weight: 800;
        margin: 0 0 6px;
    }
    .sidebar-promo-card p {
        font-size: 12.5px;
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.5;
        margin-bottom: 16px;
    }
    .btn-promo-cta {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ffffff;
        color: #2563eb !important;
        font-size: 13px;
        font-weight: 800;
        padding: 9px 20px;
        border-radius: 25px;
        text-decoration: none !important;
        transition: all 0.2s;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    }
    .btn-promo-cta:hover {
        background: #f8fafc;
        transform: translateY(-2px);
    }

    /* Sidebar Ad Card */
    .sidebar-ad-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px;
        text-align: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }
    .ad-label {
        display: block;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }
    .ad-thumb-link img {
        max-width: 100%;
        max-height: 160px;
        width: auto;
        height: auto;
        object-fit: contain;
        display: block;
        margin: 0 auto;
        border-radius: 8px;
    }

    /* Latest Articles Widget */
    .sidebar-widget-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }
    .widget-header {
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 12px;
        margin-bottom: 16px;
    }
    .widget-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .latest-articles-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .latest-article-row {
        display: flex;
        align-items: center;
        gap: 14px;
        text-decoration: none !important;
        transition: all 0.2s ease;
        padding-bottom: 14px;
        border-bottom: 1px solid #f8fafc;
    }
    .latest-article-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .latest-thumb-wrap {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        overflow: hidden;
        background: #f8fafc;
        flex-shrink: 0;
        border: 1px solid #edf2f7;
    }
    .latest-thumb-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.25s ease;
    }
    .latest-article-row:hover .latest-thumb-wrap img {
        transform: scale(1.08);
    }
    .latest-content-wrap {
        flex: 1;
    }
    .latest-row-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.4;
        margin: 0 0 4px;
        transition: color 0.2s;
    }
    .latest-article-row:hover .latest-row-title {
        color: #2563eb;
    }
    .latest-row-date {
        font-size: 11.5px;
        color: #94a3b8;
    }

    /* Mobile Responsive Tweaks */
    @media (max-width: 991px) {
        .blog-main-card {
            padding: 22px;
        }
        .article-title {
            font-size: 23px;
        }
        .product-review-card {
            flex-direction: column;
            text-align: center;
            gap: 16px;
        }
        .product-header-line {
            justify-content: center;
        }
        .product-specs-list {
            justify-content: center;
        }
        .product-action-row {
            justify-content: center;
        }
        .cashback-action-banner {
            flex-direction: column;
            text-align: center;
        }
        .banner-buttons {
            justify-content: center;
        }
        .blog-sidebar-sticky {
            position: static;
        }
    }
</style>
@endpush

@push('script')
<script>
    // Copy Article URL Handler
    function copyArticleUrl(btn) {
        const textSpan = btn.querySelector('.copy-text');
        navigator.clipboard.writeText(window.location.href).then(() => {
            btn.style.borderColor = '#10b981';
            btn.style.color = '#10b981';
            textSpan.innerText = 'Copied!';
            setTimeout(() => {
                btn.style.borderColor = '';
                btn.style.color = '';
                textSpan.innerText = 'Copy Link';
            }, 2500);
        });
    }

    // Client-side auto-cleaner for generic blog content
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('articleRichBody');
        if (!container) return;

        // Auto-wrap raw tables with responsive scrollable container
        container.querySelectorAll('table').forEach(tbl => {
            tbl.classList.add('styled-comparison-table');
            if (!tbl.closest('.table-responsive-wrapper')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'table-responsive-wrapper';
                tbl.parentNode.insertBefore(wrapper, tbl);
                wrapper.appendChild(tbl);
            }
        });

        // Hide broken temp images
        container.querySelectorAll('img').forEach(img => {
            if (img.src.startsWith('file:') || img.src.includes('AppData') || img.src.includes('msohtmlclip')) {
                img.remove();
            }
            img.onerror = function() {
                this.remove();
            };
        });

        // Clean empty <p> tags
        container.querySelectorAll('p').forEach(p => {
            const trimmed = p.innerHTML.replace(/&nbsp;/g, '').trim();
            if (trimmed === '' || trimmed === '<br>') {
                p.remove();
            }
        });
    });
</script>
@endpush