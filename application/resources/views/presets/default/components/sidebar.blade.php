<style>
    /* Exact Match Sidebar UI */
    .side-bar-wrap {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 15px 0 0 !important;
        font-family: 'Inter', sans-serif;
        top: 130px !important; /* Adjusted so it is not cut off by the header */
    }
    
    /* Search Box */
    .section-search-box {
        margin-top: 5px;
        margin-bottom: 25px;
    }
    .section-search-box form {
        position: relative;
    }
    .section-search-box .form--control {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 13px;
        color: #334155;
        background: #ffffff;
        box-shadow: none;
    }
    .section-search-box .form--control::placeholder {
        color: #94a3b8;
    }
    .section-search-box .form--control:focus {
        border-color: #cbd5e1;
        outline: none;
    }
    .section-search-box button {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 15px;
        padding: 0;
    }

    /* Categories/Stores Sections */
    .categories {
        margin-bottom: 25px !important;
        border: none !important;
        padding: 0 !important;
    }
    .categories .title {
        font-size: 14px !important;
        font-weight: 600 !important;
        color: #0f172a !important;
        margin-bottom: 15px !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 12px !important;
        border-bottom: 1px solid #e2e8f0 !important;
        text-transform: none !important;
        letter-spacing: normal !important;
    }
    /* Downward chevron arrow */
    .categories .title::after {
        content: '\f107'; /* FontAwesome angle-down */
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 12px;
        color: #334155;
        display: block !important;
        width: auto !important;
        height: auto !important;
        background: none !important;
        margin: 0 !important;
    }

    /* Scrollable List container */
    .category-list {
        max-height: 240px;
        overflow-y: auto;
        padding-right: 15px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    /* Custom Scrollbar to match screenshot */
    .category-list::-webkit-scrollbar {
        width: 8px;
    }
    .category-list::-webkit-scrollbar-track {
        background: #f8fafc; 
    }
    .category-list::-webkit-scrollbar-thumb {
        background: #cbd5e1; 
        border-radius: 4px;
    }

    /* Checkbox Design */
    .form--check {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        margin-bottom: 0 !important;
        cursor: pointer;
    }
    
    /* Native Checkbox Styled */
    .form-check-input {
        width: 16px !important;
        height: 16px !important;
        min-width: 16px !important;
        margin-right: 12px !important;
        margin-top: 0 !important;
        cursor: pointer;
        border: 1px solid #cbd5e1 !important;
        border-radius: 3px !important;
        accent-color: #0ea5e9 !important; /* Sky blue */
        display: inline-block !important;
    }
    
    .form-check-label {
        font-size: 13.5px !important;
        color: #334155 !important;
        cursor: pointer;
        font-weight: 400 !important;
        margin: 0 !important;
        line-height: 1.4 !important;
        display: inline-block !important;
        width: auto !important;
        white-space: nowrap !important;
    }
    
    /* Ad banners (styled like the Google preferred source button) */
    .sidebar-add-wrap {
        margin-top: 20px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        padding: 5px;
    }
    .sidebar-add-wrap img {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 4px;
    }

    /* Show more buttons hidden inside list since it scrolls */
    .show-more-button, .show-more-button-stores {
        display: none !important; 
    }
    
    /* Force all items to be visible so the scrollbar works (overrides JS hide logic) */
    .check-item, .check-item-stores {
        display: block !important;
    }
</style>

<div class="side-bar-wrap">
    <div class="section-search-box mb-4">
        <form>
            <input class="form--control" id="searchValue" name="search" type="text" placeholder="@lang('Search')">
            <button><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>
    @php
        $cats = $sidebarCategories ?? $categories ?? [];
        $catPrefix = $categoryPrefix ?? 'categories_';
        $stors = $sidebarStores ?? $stores ?? [];
        $storPrefix = $storePrefix ?? 'stores_';
    @endphp

    @if(!empty($cats) && count($cats) > 0)
    <div class="categories">
        <h6 class="title">@lang('Categories')</h6>
        <div class="category-list">
            @php
                $categoryCount = count($cats);
            @endphp
            @foreach ($cats as $item)
                <div class="check-item">
                    <div class="form--check categories-search mb-2">
                        <input class="form-check-input filter-by-category" name="{{$catPrefix}}{{$loop->iteration}}" type="checkbox" value="{{ $item->id ?? $item->title ?? '' }}" id="{{$catPrefix}}{{$loop->iteration}}">
                        <label for="{{$catPrefix}}{{$loop->iteration}}" class="form-check-label">{{ $item->name ?? $item->title ?? '' }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($stors) && count($stors) > 0)
    <div class="categories">
        <h6 class="title">@lang('Stores')</h6>
        <div class="category-list">
            @php
                $storeCount = count($stors);
            @endphp
            @foreach ($stors as $item)
            <div class="check-item-stores">
                <div class="form--check categories-search mb-2">
                    <input class="form-check-input filter-by-stores" id="{{$storPrefix}}{{$loop->iteration}}" name="{{$storPrefix}}{{$loop->iteration}}"
                        type="checkbox" value="{{ $item->id ?? '' }}">
                    <label for="{{$storPrefix}}{{$loop->iteration}}" class="form-check-label">{{ $item->name ?? '' }}</label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @php
        $firstAd = App\Models\Ad::first();
        $secondAd = App\Models\Ad::skip(1)->first();
    @endphp
    @if($firstAd)
        <div class="categories">
            <!-- ad image start -->
            <div class="sidebar-add-wrap position-relative">
            <div class="long-add-wrap--thumb">
                    <a href="{{@$firstAd->link}}" target="_blank">
                        <img src="{{ getImage(getFilePath('adImage') . '/' .@$firstAd->image) }}" alt="">
                    </a>
            </div>
            </div>
            <!-- ad image end -->
        </div>
    @endif
    @if($secondAd)
        <div class="categories">
            <!-- ad image start -->
            <div class="sidebar-add-wrap position-relative">
            <div class="long-add-wrap--thumb">
                    <a href="{{@$secondAd->link}}" target="_blank">
                        <img src="{{ getImage(getFilePath('adImage') . '/' . @$secondAd->image) }}" alt="">
                    </a>
            </div>
            </div>
            <!-- ad image end -->
        </div>
    @endif
</div>
