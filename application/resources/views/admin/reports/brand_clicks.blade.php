@extends('admin.layouts.app')

@section('panel')
<div class="row gy-4">

    {{-- KPI SUMMARY CARDS --}}
    <div class="col-12">
        <div class="row g-3">
            <div class="col-xl-3 col-sm-6">
                <div class="card shadow-sm border-0 b-radius--10 bg-white p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-bold">@lang('TOTAL BRAND CLICKS')</span>
                            <h3 class="mb-0 text--primary fw-bold mt-1">{{ number_format($totalClicks) }}</h3>
                        </div>
                        <div class="rounded-circle p-3 bg-light text--primary fs-24">
                            <i class="las la-mouse-pointer"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card shadow-sm border-0 b-radius--10 bg-white p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-bold">@lang('CONVERTED (ORDERS)')</span>
                            <h3 class="mb-0 text--success fw-bold mt-1">{{ number_format($totalConverted) }}</h3>
                        </div>
                        <div class="rounded-circle p-3 bg-light text--success fs-24">
                            <i class="las la-shopping-bag"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card shadow-sm border-0 b-radius--10 bg-white p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-bold">@lang('CONVERSION RATE (CR)')</span>
                            <h3 class="mb-0 text--warning fw-bold mt-1">{{ $crRate }}%</h3>
                        </div>
                        <div class="rounded-circle p-3 bg-light text--warning fs-24">
                            <i class="las la-chart-pie"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card shadow-sm border-0 b-radius--10 bg-white p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-bold">@lang('TOP CLICKED BRAND')</span>
                            <h5 class="mb-0 text--dark fw-bold mt-1 text-truncate" style="max-width: 170px;">
                                {{ $topStore ? $topStore->name : 'N/A' }}
                            </h5>
                        </div>
                        <div class="rounded-circle p-3 bg-light text--dark fs-24">
                            <i class="las la-store"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER CARD --}}
    <div class="col-12">
        <div class="card b-radius--10 shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom flex-wrap gap-2">
                <h6 class="card-title mb-0 text--dark">
                    <i class="las la-filter text--primary fs-18"></i> @lang('Filter Brand Clicks & Export')
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.report.brand.clicks.export', request()->all()) }}" class="btn btn-sm btn--success" title="@lang('Export filtered clicks to CSV')">
                        <i class="las la-file-csv fs-15"></i> @lang('Export CSV')
                    </a>
                </div>
            </div>

            <div class="card-body p-4 bg-light bg-opacity-25">
                <form action="{{ route('admin.report.brand.clicks') }}" method="GET">
                    <div class="row g-3 align-items-end">

                        {{-- 1. Filter by User --}}
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <label class="form-label fw-bold text-dark fs-13">
                                <i class="las la-user text--primary"></i> @lang('User')
                            </label>
                            <select name="user_id" class="form-select form-control">
                                <option value="">@lang('-- All Users --')</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ request()->user_id == $u->id ? 'selected' : '' }}>
                                        {{ $u->username }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. Filter by Brand/Store --}}
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <label class="form-label fw-bold text-dark fs-13">
                                <i class="las la-store text--primary"></i> @lang('Brand / Store')
                            </label>
                            <select name="store_id" class="form-select form-control">
                                <option value="">@lang('-- All Brands --')</option>
                                @foreach($stores as $st)
                                    <option value="{{ $st->id }}" {{ request()->store_id == $st->id ? 'selected' : '' }}>
                                        {{ $st->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. Converted Filter --}}
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <label class="form-label fw-bold text-dark fs-13">
                                <i class="las la-check-circle text--primary"></i> @lang('Conversion Status')
                            </label>
                            <select name="converted" class="form-select form-control">
                                <option value="">@lang('-- All Clicks --')</option>
                                <option value="yes" {{ request()->converted == 'yes' ? 'selected' : '' }}>@lang('Converted (Order Placed)')</option>
                                <option value="no" {{ request()->converted == 'no' ? 'selected' : '' }}>@lang('Clicks Only (No Order)')</option>
                            </select>
                        </div>

                        {{-- 4. From Date --}}
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <label class="form-label fw-bold text-dark fs-13">
                                <i class="las la-calendar text--primary"></i> @lang('From Date')
                            </label>
                            <input type="date" name="start_date" class="form-control" value="{{ request()->start_date }}">
                        </div>

                        {{-- 5. To Date --}}
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <label class="form-label fw-bold text-dark fs-13">
                                <i class="las la-calendar text--primary"></i> @lang('To Date')
                            </label>
                            <input type="date" name="end_date" class="form-control" value="{{ request()->end_date }}">
                        </div>

                        {{-- 6. Keyword Search --}}
                        <div class="col-xl-6 col-md-8">
                            <label class="form-label fw-bold text-dark fs-13">
                                <i class="las la-search text--primary"></i> @lang('Search (Click ID, Username, Email, Brand, IP)')
                            </label>
                            <input type="text" name="search" class="form-control" placeholder="@lang('e.g. clk_flipkart, aditya, 157.37...')" value="{{ request()->search }}">
                        </div>

                        {{-- 7. Action Buttons --}}
                        <div class="col-xl-6 col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn--primary flex-grow-1">
                                <i class="las la-filter"></i> @lang('Apply Filters')
                            </button>
                            <a href="{{ route('admin.report.brand.clicks') }}" class="btn btn--secondary" title="@lang('Reset filters')">
                                <i class="las la-redo"></i> @lang('Reset')
                            </a>
                            <a href="{{ route('admin.report.brand.clicks.export', request()->all()) }}" class="btn btn--success" title="@lang('Export')">
                                <i class="las la-file-download"></i> @lang('Export')
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- GRANULAR CLICKS TABLE --}}
    <div class="col-12">
        <div class="card b-radius--10 shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom flex-wrap gap-2">
                <div>
                    <h5 class="card-title mb-0 text--dark">
                        <i class="las la-list-alt text--primary"></i> @lang('Brand Clicks Log Stream')
                    </h5>
                    <div class="text-muted small mt-1">
                        @lang('Showing') <strong>{{ $clicks->firstItem() ?? 0 }}</strong> @lang('to') <strong>{{ $clicks->lastItem() ?? 0 }}</strong> @lang('of') <strong>{{ number_format($clicks->total()) }}</strong> @lang('recorded clicks')
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table--light style--two text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>@lang('Tracking IDs (Click & Sub ID)')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Brand / Store')</th>
                                <th>@lang('Offer / Destination')</th>
                                <th>@lang('Device & IP')</th>
                                <th>@lang('Converted')</th>
                                <th>@lang('Clicked Time')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clicks as $click)
                            <tr>
                                {{-- Click ID & Sub ID --}}
                                <td>
                                    <div>
                                        <span class="badge badge--primary fs-11 font-monospace" title="@lang('Click ID')">
                                            <i class="las la-mouse-pointer"></i> {{ $click->click_id }}
                                        </span>
                                    </div>
                                    <div class="mt-1">
                                        <span class="badge badge--info fs-11 font-monospace" title="@lang('Sub ID')">
                                            <i class="las la-tag"></i> {{ $click->sub_id ?: ('sub_' . substr($click->click_id, 4)) }}
                                        </span>
                                    </div>
                                </td>

                                {{-- User --}}
                                <td>
                                    @if($click->user)
                                        <div>
                                            <a href="{{ route('admin.users.detail', $click->user_id) }}" class="fw-bold text--dark">
                                                {{ $click->user->username }}
                                            </a>
                                        </div>
                                        <div class="text-muted small fs-11">{{ $click->user->email }}</div>
                                    @else
                                        <span class="text-muted small">@lang('Guest / Deleted User')</span>
                                    @endif
                                </td>

                                {{-- Brand / Store --}}
                                <td>
                                    @php
                                        $store = $click->store ?? (@$click->coupon->store ?? @$click->deal->store);
                                    @endphp
                                    <div class="d-flex align-items-center gap-2">
                                        @if(@$store->image)
                                            <img src="{{ getImage(getFilePath('store') . '/' . $store->image) }}" alt="store" style="width: 34px; height: 34px; object-fit: contain; border-radius: 6px; border: 1px solid #edf2f7; background: #fff; padding: 2px;">
                                        @endif
                                        <span class="fw-bold text--dark fs-13">{{ @$store->name ?? (@$click->campaign ?? 'Direct Store') }}</span>
                                    </div>
                                </td>

                                {{-- Offer / Destination --}}
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ @$click->coupon->title ?? (@$click->deal->title ?? $click->destination_url) }}">
                                        {{ @$click->coupon->title ?? (@$click->deal->title ?? 'Store Visit') }}
                                    </div>
                                    @if($click->destination_url)
                                        <a href="{{ $click->destination_url }}" target="_blank" class="text-muted small fs-11" title="@lang('External URL')">
                                            <i class="las la-external-link-alt"></i> {{ substr($click->destination_url, 0, 30) }}...
                                        </a>
                                    @endif
                                </td>

                                {{-- Device & IP --}}
                                <td>
                                    <div>
                                        @if($click->device_type === 'mobile')
                                            <span class="badge badge--info py-1 px-2"><i class="las la-mobile"></i> @lang('Mobile')</span>
                                        @else
                                            <span class="badge badge--light border py-1 px-2"><i class="las la-desktop"></i> @lang('Desktop')</span>
                                        @endif
                                        <span class="text-dark small ms-1">{{ $click->browser ?: 'Browser' }}</span>
                                    </div>
                                    <div class="text-muted small fs-11 mt-1">
                                        <i class="las la-network-wired"></i> {{ $click->ip_address }}
                                    </div>
                                </td>

                                {{-- Converted --}}
                                <td>
                                    @if($click->order_id)
                                        <span class="badge badge--success py-1 px-2">
                                            <i class="las la-check-circle"></i> @lang('Order') #{{ $click->order_id }}
                                        </span>
                                    @else
                                        <span class="badge badge--light border text-muted py-1 px-2">
                                            @lang('Click Only')
                                        </span>
                                    @endif
                                </td>

                                {{-- Clicked Time --}}
                                <td>
                                    <div class="fs-12 fw-semibold text--dark">
                                        {{ showDateTime($click->created_at, 'd M Y, h:i A') }}
                                    </div>
                                    <div class="text-muted fs-11">
                                        {{ diffForHumans($click->created_at) }}
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center py-4" colspan="100%">
                                    <i class="las la-info-circle fs-18"></i> @lang('No brand click activity matches the selected filters.')
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($clicks->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($clicks) }}
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('breadcrumb-plugins')
<div class="d-flex flex-wrap gap-2 align-items-center">
    <a href="{{ route('admin.report.brand.clicks.export', request()->all()) }}" class="btn btn-sm btn--success">
        <i class="las la-file-csv fs-15"></i> @lang('Export to CSV')
    </a>
</div>
@endpush
