@php
    $customModel = App\Models\CustomSection::where('key', $secKey)->first();
    $customContent = getContent($secKey . '.content', true);
    $customElements = getContent($secKey . '.element');
    $layoutType = $customModel->layout_type ?? 'card_grid';
    $heading = @$customContent->data_values->heading ?: ($customModel->heading ?: $customModel->name);
    $subheading = @$customContent->data_values->subheading ?: ($customModel->subheading ?: '');
@endphp

@if($customModel && $customModel->status)
<section class="custom-dynamic-section" id="section-{{ $secKey }}">
    <div class="container-fluid container-custom">
        {{-- Section Header: Exactly matching Most Popular Brands & other homepage sections --}}
        @if($heading)
            <div class="row">
                <div class="col-lg-12">
                    <div class="custom-sec-header-wrap">
                        <div>
                            <h2 class="section-title mb-0 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                                {{ __($heading) }}
                            </h2>
                            @if($subheading && strtolower(trim($subheading)) !== strtolower(trim($heading)))
                                <small class="text-muted fs-13 d-block mt-1">{{ __($subheading) }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Layout 1: Card Grid (Matching Site 6-Column Card Standard) --}}
        @if($layoutType === 'card_grid')
            <div class="custom-cards-grid">
                @forelse($customElements as $item)
                    @php
                        $itemTitle = @$item->data_values->title;
                        $itemTag = @$item->data_values->sale_tag ?: 'Sale Live Now';
                        $itemCashback = @$item->data_values->cashback_text ?: 'Grab Offer';
                        $itemLink = @$item->data_values->link ?: 'javascript:void(0)';
                        $itemImg = getImage('assets/images/frontend/' . $secKey . '/' . @$item->data_values->item_image, '400x300');
                    @endphp
                    <div class="custom-grid-item">
                        <a href="{{ $itemLink }}" class="custom-brand-card">
                            <div class="custom-brand-tag">
                                {{ __($itemTag) }}
                            </div>
                            <div class="custom-brand-logo-wrap">
                                <img src="{{ $itemImg }}" alt="{{ __($itemTitle) }}" loading="lazy">
                            </div>
                            <div class="custom-brand-btn-wrap">
                                <span class="custom-brand-btn">
                                    {{ __($itemCashback) }}
                                </span>
                            </div>
                        </a>
                    </div>
                @empty
                    @if(auth()->guard('admin')->check())
                        <div class="col-12 text-center py-4 text-muted bg-light rounded border">
                            <p class="mb-0 fs-13">@lang('No items added yet to') <strong>{{ $heading }}</strong>. <a href="{{ route('admin.frontend.sections', $secKey) }}" class="text--base fw-bold">@lang('Click here to add items in Admin Panel')</a>.</p>
                        </div>
                    @endif
                @endforelse
            </div>

        {{-- Layout 2: Banner Slider --}}
        @elseif($layoutType === 'banner_slider')
            <div class="row g-3">
                @forelse($customElements as $item)
                    @php
                        $itemTitle = @$item->data_values->title;
                        $itemLink = @$item->data_values->link ?: 'javascript:void(0)';
                        $itemImg = getImage('assets/images/frontend/' . $secKey . '/' . @$item->data_values->item_image, '1920x450');
                    @endphp
                    <div class="col-md-6 col-12">
                        <a href="{{ $itemLink }}" class="d-block overflow-hidden rounded-3 border shadow-sm" target="_blank">
                            <img src="{{ $itemImg }}" alt="{{ $itemTitle }}" class="img-fluid w-100" style="object-fit: cover;">
                        </a>
                    </div>
                @empty
                    @if(auth()->guard('admin')->check())
                        <div class="col-12 text-center py-4 text-muted bg-light rounded border">
                            <p class="mb-0 fs-13">@lang('No banners added yet.') <a href="{{ route('admin.frontend.sections', $secKey) }}" class="text--base fw-bold">@lang('Add Banners')</a>.</p>
                        </div>
                    @endif
                @endforelse
            </div>

        {{-- Layout 3: Promo Box --}}
        @elseif($layoutType === 'promo_box')
            <div class="custom-promo-box p-4 rounded-3 border shadow-sm bg-white">
                <div class="row align-items-center g-3">
                    @forelse($customElements as $item)
                        @php
                            $itemTitle = @$item->data_values->title;
                            $itemTag = @$item->data_values->sale_tag;
                            $itemCashback = @$item->data_values->cashback_text;
                            $itemLink = @$item->data_values->link ?: 'javascript:void(0)';
                            $itemImg = getImage('assets/images/frontend/' . $secKey . '/' . @$item->data_values->item_image, '800x400');
                        @endphp
                        <div class="col-md-5 text-center">
                            <img src="{{ $itemImg }}" alt="{{ $itemTitle }}" class="img-fluid rounded-2" style="max-height: 220px; object-fit: contain;">
                        </div>
                        <div class="col-md-7">
                            @if($itemTag)
                                <span class="badge bg-primary text-white fs-12 px-2 py-1 mb-2">{{ $itemTag }}</span>
                            @endif
                            <h3 class="fw-bold text-dark mb-2">{{ $itemTitle }}</h3>
                            @if($itemCashback)
                                <p class="text-success fw-bold fs-16 mb-3">{{ $itemCashback }}</p>
                            @endif
                            <a href="{{ $itemLink }}" class="btn btn--base px-4 py-2" target="_blank">
                                @lang('Explore Now') <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        </div>
                    @empty
                        @if(auth()->guard('admin')->check())
                            <div class="col-12 text-center py-4 text-muted bg-light rounded border">
                                <p class="mb-0 fs-13">@lang('No promo items added.') <a href="{{ route('admin.frontend.sections', $secKey) }}" class="text--base fw-bold">@lang('Add Items')</a>.</p>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</section>

@push('style')
<style>
/* Custom Dynamic Section - Perfectly matching Most Popular Brands */
.custom-dynamic-section {
    position: relative;
    padding-top: 28px;
    padding-bottom: 24px;
    margin-top: 10px;
    margin-bottom: 20px;
}

.popular-brands-section + .custom-dynamic-section,
.custom-dynamic-section + .custom-dynamic-section {
    margin-top: 0px;
    padding-top: 28px;
}

.custom-sec-header-wrap {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

/* Responsive Grid matching popular_brands (6 Columns on desktop) */
.custom-cards-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    grid-gap: 15px;
}
.custom-grid-item {
    outline: none !important;
    height: 100%;
}
.custom-brand-card {
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
.custom-brand-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 26px rgba(0, 71, 255, 0.14), 0 4px 10px rgba(0, 0, 0, 0.04);
    border-color: #bfdbfe;
}
.custom-brand-tag {
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
.custom-brand-logo-wrap {
    height: 90px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px 15px;
    background: #ffffff;
}
.custom-brand-logo-wrap img {
    max-width: 120px;
    max-height: 52px;
    object-fit: contain;
    transition: transform 0.3s ease;
}
.custom-brand-card:hover .custom-brand-logo-wrap img {
    transform: scale(1.06);
}
.custom-brand-btn-wrap {
    padding: 0 12px 14px 12px;
}
.custom-brand-btn {
    display: block;
    width: 100%;
    background: #0053c8;
    color: #ffffff !important;
    text-align: center;
    font-size: 13px;
    font-weight: 700;
    padding: 9px 8px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 83, 200, 0.25);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: all 0.25s ease;
}
.custom-brand-card:hover .custom-brand-btn {
    background: #003fa0;
    box-shadow: 0 6px 16px rgba(0, 83, 200, 0.38);
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .custom-cards-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}
@media (max-width: 900px) {
    .custom-cards-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
@media (max-width: 600px) {
    .custom-cards-grid {
        grid-template-columns: repeat(2, 1fr);
        grid-gap: 10px;
    }
    .custom-brand-card {
        height: 165px;
        border-radius: 14px;
    }
    .custom-brand-tag {
        font-size: 10.5px;
        padding: 4px 6px;
    }
    .custom-brand-logo-wrap {
        height: 68px;
        padding: 6px 10px;
    }
    .custom-brand-logo-wrap img {
        max-width: 95px;
        max-height: 38px;
    }
    .custom-brand-btn-wrap {
        padding: 0 8px 10px 8px;
    }
    .custom-brand-btn {
        font-size: 11.5px;
        padding: 7px 6px;
        border-radius: 8px;
    }
}
</style>
@endpush
@endif
