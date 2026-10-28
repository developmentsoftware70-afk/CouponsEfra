@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
    $firstAd = App\Models\Ad::first();
    $secondAd = App\Models\Ad::skip(1)->first();
@endphp

<style>
    /* Card Styles */
    .vertical-coupon-card {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 24px;
        height: 220px;
        display: block;
        background: #f8f9fa;
        border: 1px solid #eaeaea;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .vertical-coupon-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        transform: translateY(-3px);
    }
    .vertical-card-img {
        width: 100%;
        height: 100%;
    }
    .vertical-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .vertical-grab-btn {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: #0053c8;
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        font-size: 14px;
        z-index: 2;
        transition: background 0.2s;
    }
    .vertical-grab-btn:hover {
        background: #003a8c;
        color: #fff;
    }

    /* Filter Button (Only on Phone) */
    .filter-trigger-btn {
        background: #ffffff;
        border: 1.5px solid #0053c8;
        color: #0053c8;
        font-weight: 600;
        font-size: 14px;
        padding: 8px 18px;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .filter-trigger-btn:hover, .filter-trigger-btn.has-active {
        background: #0053c8;
        color: #ffffff;
    }
    .filter-trigger-btn .filter-count-badge {
        background: #0053c8;
        color: #ffffff;
        font-size: 11px;
        padding: 2px 7px;
        border-radius: 10px;
        font-weight: 700;
    }
    .filter-trigger-btn:hover .filter-count-badge,
    .filter-trigger-btn.has-active .filter-count-badge {
        background: #ffffff;
        color: #0053c8;
    }

    /* Filter Drawer & Overlay */
    .deal-filter-overlay {
        position: fixed;
        top: 120px;
        left: 0;
        width: 100vw;
        height: calc(100vh - 120px);
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(3px);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .deal-filter-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    .deal-filter-drawer {
        position: fixed;
        top: 120px;
        right: -380px;
        width: 360px;
        max-width: 90vw;
        height: calc(100vh - 120px);
        background: #ffffff;
        z-index: 9999;
        box-shadow: -8px 0 30px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .deal-filter-drawer.active {
        right: 0;
    }
    .filter-drawer-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
    }
    .filter-drawer-header h5 {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .filter-drawer-body {
        padding: 20px;
        overflow-y: auto;
        flex: 1;
    }
    .filter-drawer-body::-webkit-scrollbar {
        width: 6px;
    }
    .filter-drawer-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    .filter-drawer-footer {
        padding: 16px 20px;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        gap: 10px;
    }
    .filter-group-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .filter-checkbox-list {
        max-height: 320px;
        overflow-y: auto;
        padding-right: 8px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 20px;
    }
    .filter-checkbox-list::-webkit-scrollbar {
        width: 5px;
    }
    .filter-checkbox-list::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 3px;
    }
    .filter-checkbox-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 4px 0;
        cursor: pointer;
    }
    .filter-checkbox-item input[type="checkbox"] {
        width: 17px;
        height: 17px;
        border-radius: 4px;
        cursor: pointer;
        accent-color: #0053c8;
    }
    .filter-checkbox-item label {
        font-size: 13.5px;
        color: #334155;
        cursor: pointer;
        margin: 0;
        user-select: none;
    }
    body.drawer-open {
        overflow: hidden;
    }
</style>

<section class="exclusive-section py-20">
    <div class="container-fluid container-custom">

        {{-- Filter button: ONLY visible on Phone/Mobile (Hidden on Laptop) --}}
        <div class="d-lg-none mb-3 d-flex justify-content-start">
            <button type="button" class="filter-trigger-btn" id="openDealFilterBtn">
                <i class="las la-sliders-h" style="font-size: 18px;"></i>
                <span>@lang('Filter')</span>
                <span class="filter-count-badge d-none" id="activeDealFilterBadge">0</span>
            </button>
        </div>

        <div class="row gy-4 justify-content-center">
            {{-- Laptop / Desktop: Sidebar is visible on left --}}
            <div class="col-lg-3 d-none d-lg-block">
                @include($activeTemplate.'components.sidebar', ['sidebarCategories' => $dealCategories ?? [], 'categoryPrefix' => 'deal_categories_'])
            </div>

            {{-- Deals Cards: 3 per row on Desktop next to sidebar, full width on Mobile --}}
            <div class="col-lg-9 col-12 main-content">
                <div class="row gy-4">
                    @forelse($deals as $index => $item)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="vertical-coupon-card wow animate__animated animate__fadeInUp" data-wow-delay="{{ 0.1 + ($index * 0.05) }}s">
                            <div class="vertical-card-img">
                                @if($item->image)
                                    <img src="{{ getImage(getFilePath('deal') . '/' . @$item->image) }}" alt="">
                                @else
                                    <img src="{{ getImage(getFilePath('dealCategory') . '/' . @$item->dealCategory->image) }}" alt="">
                                @endif
                            </div>
                            
                            <a href="{{ route('deal.details', $item->id) }}" class="vertical-grab-btn">
                                {{ $item->button_label ?? __('Grab Deal') }}
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5 bg-white rounded-3 border">
                            <p class="text-muted mb-0">{{ __($emptyMessage ?? 'No deals found') }}</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="row py-4">
            @if ($deals->hasPages())
                <div class="py-4">
                    {{ paginateLinks($deals) }}
                </div>
            @endif
        </div>
    </div>
</section>

{{-- MODERN DEAL FILTER DRAWER (Used on Phone) --}}
<div class="deal-filter-overlay" id="dealFilterOverlay"></div>
<div class="deal-filter-drawer" id="dealFilterDrawer">
    <div class="filter-drawer-header">
        <h5><i class="las la-sliders-h text-primary me-1"></i> @lang('Filter Deals')</h5>
        <div class="d-flex align-items-center gap-3">
            <button type="button" class="btn btn-sm btn-link text-decoration-none text-muted p-0" id="resetDealFiltersBtn" style="font-size: 13px; font-weight: 600;">
                @lang('Reset')
            </button>
            <button type="button" class="btn-close" id="closeDealFilterBtn" aria-label="Close"></button>
        </div>
    </div>
    <div class="filter-drawer-body">
        <div class="mb-3">
            <label class="filter-group-title">
                <span>@lang('Search Keyword')</span>
            </label>
            <div class="position-relative">
                <input type="text" class="form-control form-control-sm" id="dealSearchValue" placeholder="@lang('Search deals...')">
            </div>
        </div>

        @if(!empty($dealCategories) && count($dealCategories) > 0)
        <div class="mb-4">
            <label class="filter-group-title">
                <span><i class="las la-folder me-1 text-primary"></i> @lang('Deal Categories')</span>
                <span class="text-muted fw-normal" style="font-size: 11px;">({{ count($dealCategories) }})</span>
            </label>
            <div class="filter-checkbox-list">
                @foreach ($dealCategories as $item)
                    <div class="filter-checkbox-item">
                        <input class="filter-deal-category" name="deal_categories_{{ $loop->iteration }}" type="checkbox" value="{{ $item->id }}" id="deal_cat_{{ $item->id }}">
                        <label for="deal_cat_{{ $item->id }}">{{ $item->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    <div class="filter-drawer-footer">
        <button type="button" class="btn btn--base w-100 py-2" id="applyDealFilterBtn" style="font-weight: 600; border-radius: 8px;">
            @lang('Apply Filters')
        </button>
    </div>
</div>

@endsection

@push('script')
<script>
    (function($) {
        "use strict";

        // Filter Drawer Open & Close
        function openDrawer() {
            var headerEl = document.getElementById('header') || document.querySelector('.header');
            var h = headerEl ? headerEl.offsetHeight : 120;
            $('#dealFilterDrawer, #dealFilterOverlay')
                .css('top', h + 'px')
                .css('height', 'calc(100vh - ' + h + 'px)')
                .addClass('active');
            $('body').addClass('drawer-open');
        }
        function closeDrawer() {
            $('#dealFilterDrawer, #dealFilterOverlay').removeClass('active');
            $('body').removeClass('drawer-open');
        }

        $('#openDealFilterBtn').on('click', function(e) {
            e.preventDefault();
            openDrawer();
        });

        $('#closeDealFilterBtn, #dealFilterOverlay, #applyDealFilterBtn').on('click', function(e) {
            e.preventDefault();
            closeDrawer();
        });

        function updateBadge() {
            var count = $('.filter-deal-category:checked, .filter-by-category:checked').length;
            if (count > 0) {
                $('#activeDealFilterBadge').text(count).removeClass('d-none');
                $('#openDealFilterBtn').addClass('has-active');
            } else {
                $('#activeDealFilterBadge').text('0').addClass('d-none');
                $('#openDealFilterBtn').removeClass('has-active');
            }
        }

        function runDealFilter() {
            var dealCategories = [];
            var searchValue = $('#dealSearchValue').val() || $('#searchValue').val() || '';

            $('.filter-deal-category:checked, .filter-by-category:checked').each(function() {
                var val = parseInt($(this).val());
                if (!dealCategories.includes(val)) {
                    dealCategories.push(val);
                }
            });

            updateBadge();

            $.ajax({
                type: "get",
                url: "{{ route('deal.filtered') }}",
                data: {
                    "dealCategories": dealCategories,
                    "search": searchValue
                },
                dataType: "json",
                beforeSend: function() {
                    $('.main-content').css('opacity', '0.5');
                },
                success: function (response) {
                    $('.main-content').css('opacity', '1');
                    if (response.html) {
                        $('.main-content').html(response.html);
                    }
                    if (response.error) {
                        notify('error', response.error);
                    }
                },
                error: function() {
                    $('.main-content').css('opacity', '1');
                }
            });
        }

        var searchTimeout = null;
        $("#dealSearchValue, #searchValue").on('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                runDealFilter();
            }, 300);
        });

        // Trigger on both drawer checkboxes and desktop sidebar checkboxes
        $(document).on('change', '.filter-deal-category, .filter-by-category', function(){
            runDealFilter();
        });

        $('#resetDealFiltersBtn').on('click', function(e) {
            e.preventDefault();
            $('.filter-deal-category, .filter-by-category').prop('checked', false);
            $('#dealSearchValue, #searchValue').val('');
            updateBadge();
            runDealFilter();
        });

    })(jQuery);
</script>
@endpush
