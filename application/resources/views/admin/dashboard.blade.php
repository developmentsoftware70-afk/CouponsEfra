@extends('admin.layouts.app')

@section('panel')
@if(@json_decode($general->system_info)->message)
<div class="row">
    @foreach(json_decode($general->system_info)->message as $msg)
    <div class="col-md-12">
        <div class="alert border border--primary" role="alert">
            <div class="alert__icon bg--primary"><i class="far fa-bell"></i></div>
            <p class="alert__message">@php echo $msg; @endphp</p>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span> 
            </button>
        </div>
    </div>
    @endforeach
</div>
@endif
<div class="row gy-4">
<div class="col-12 mb-1">
<div class="row gy-4">
    <!-- Clicks Card -->
    <div class="col-xl-4 col-md-6">
        <div class="kpi-metric-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="kpi-icon-box kpi-icon-clicks">
                        <i class="las la-mouse-pointer"></i>
                    </div>
                    <span class="kpi-header-title">@lang('Clicks')</span>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="kpi-badge-link">
                    @lang('Orders') <i class="las la-angle-right"></i>
                </a>
            </div>
            <div class="kpi-stat-grid">
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('Today')</span>
                    <span class="kpi-stat-val" id="kpi-clicks-today">{{ formatShortNumber($kpiMetrics['clicks']['today']) }}</span>
                </div>
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('Yesterday')</span>
                    <span class="kpi-stat-val text-muted" id="kpi-clicks-yesterday">{{ formatShortNumber($kpiMetrics['clicks']['yesterday']) }}</span>
                </div>
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('MTD')</span>
                    <span class="kpi-stat-val text-primary" id="kpi-clicks-mtd">{{ formatShortNumber($kpiMetrics['clicks']['mtd']) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversions Card -->
    <div class="col-xl-4 col-md-6">
        <div class="kpi-metric-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="kpi-icon-box kpi-icon-conversions">
                        <i class="las la-shopping-cart"></i>
                    </div>
                    <span class="kpi-header-title">@lang('Conversions')</span>
                </div>
                <a href="{{ route('admin.orders.index') }}?status=confirmed" class="kpi-badge-link">
                    @lang('Confirmed') <i class="las la-angle-right"></i>
                </a>
            </div>
            <div class="kpi-stat-grid">
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('Today')</span>
                    <span class="kpi-stat-val text-danger" id="kpi-conversions-today">{{ formatShortNumber($kpiMetrics['conversions']['today']) }}</span>
                    <span class="kpi-stat-sub text-muted" id="kpi-cr-today">CR: {{ $kpiMetrics['conversion_rate']['today'] }}%</span>
                </div>
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('Yesterday')</span>
                    <span class="kpi-stat-val text-muted" id="kpi-conversions-yesterday">{{ formatShortNumber($kpiMetrics['conversions']['yesterday']) }}</span>
                    <span class="kpi-stat-sub text-muted" id="kpi-cr-yesterday">CR: {{ $kpiMetrics['conversion_rate']['yesterday'] }}%</span>
                </div>
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('MTD')</span>
                    <span class="kpi-stat-val text-danger" id="kpi-conversions-mtd">{{ formatShortNumber($kpiMetrics['conversions']['mtd']) }}</span>
                    <span class="kpi-stat-sub text-muted" id="kpi-cr-mtd">CR: {{ $kpiMetrics['conversion_rate']['mtd'] }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payout Card -->
    <div class="col-xl-4 col-md-6">
        <div class="kpi-metric-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="kpi-icon-box kpi-icon-payout">
                        <i class="las la-wallet"></i>
                    </div>
                    <span class="kpi-header-title">@lang('Payout')</span>
                </div>
                <a href="{{ route('admin.payouts.index') }}" class="kpi-badge-link">
                    @lang('Payouts') <i class="las la-angle-right"></i>
                </a>
            </div>
            <div class="kpi-stat-grid">
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('Today')</span>
                    <span class="kpi-stat-val text-success" id="kpi-payout-today-usd">$ {{ convertInrToUsd($kpiMetrics['payout']['today']) }}</span>
                    <span class="kpi-stat-sub" id="kpi-payout-today-inr">₹ {{ formatShortNumber($kpiMetrics['payout']['today']) }}</span>
                </div>
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('Yesterday')</span>
                    <span class="kpi-stat-val text-muted" id="kpi-payout-yesterday-usd">$ {{ convertInrToUsd($kpiMetrics['payout']['yesterday']) }}</span>
                    <span class="kpi-stat-sub" id="kpi-payout-yesterday-inr">₹ {{ formatShortNumber($kpiMetrics['payout']['yesterday']) }}</span>
                </div>
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('MTD')</span>
                    <span class="kpi-stat-val text-success" id="kpi-payout-mtd-usd">$ {{ convertInrToUsd($kpiMetrics['payout']['mtd']) }}</span>
                    <span class="kpi-stat-sub" id="kpi-payout-mtd-inr">₹ {{ formatShortNumber($kpiMetrics['payout']['mtd']) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Impressions Card -->
    <div class="col-xl-6 col-md-6">
        <div class="kpi-metric-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="kpi-icon-box kpi-icon-impressions">
                        <i class="las la-eye"></i>
                    </div>
                    <span class="kpi-header-title">@lang('Impressions')</span>
                </div>
                <span class="kpi-badge-tag">@lang('Offers')</span>
            </div>
            <div class="kpi-stat-grid">
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('Today')</span>
                    <span class="kpi-stat-val" id="kpi-impressions-today">{{ formatShortNumber($kpiMetrics['impressions']['today']) }}</span>
                </div>
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('Yesterday')</span>
                    <span class="kpi-stat-val text-muted" id="kpi-impressions-yesterday">{{ formatShortNumber($kpiMetrics['impressions']['yesterday']) }}</span>
                </div>
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('MTD')</span>
                    <span class="kpi-stat-val text-primary" id="kpi-impressions-mtd">{{ formatShortNumber($kpiMetrics['impressions']['mtd']) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Unique Logins Card -->
    <div class="col-xl-6 col-md-6">
        <div class="kpi-metric-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="kpi-icon-box kpi-icon-logins">
                        <i class="las la-user-check"></i>
                    </div>
                    <span class="kpi-header-title">@lang('Logins')</span>
                </div>
                <a href="{{ route('admin.report.login.history') }}" class="kpi-badge-link">
                    @lang('History') <i class="las la-angle-right"></i>
                </a>
            </div>
            <div class="kpi-stat-grid">
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('Today')</span>
                    <span class="kpi-stat-val text-primary" id="kpi-logins-today">{{ formatShortNumber($kpiMetrics['logins']['today']) }}</span>
                </div>
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('Yesterday')</span>
                    <span class="kpi-stat-val text-muted" id="kpi-logins-yesterday">{{ formatShortNumber($kpiMetrics['logins']['yesterday']) }}</span>
                </div>
                <div class="kpi-stat-item">
                    <span class="kpi-stat-label">@lang('MTD')</span>
                    <span class="kpi-stat-val text-primary" id="kpi-logins-mtd">{{ formatShortNumber($kpiMetrics['logins']['mtd']) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">@lang('Performance Report')</h5>
                    <div class="position-relative">
                        <button type="button" class="btn btn-outline--primary btn-sm d-flex align-items-center gap-2" id="perfDateRangeBtn">
                            <i class="las la-calendar"></i>
                            <span id="selectedRangeText">{{ $performanceData['rangeLabel'] }}</span>
                            <i class="las la-angle-down"></i>
                        </button>

                        <div id="perfDateDropdown" class="perf-dropdown-menu shadow border rounded p-3 bg-white" style="display: none; position: absolute; right: 0; top: 110%; z-index: 1050; min-width: 220px; width: 230px;">
                            <div class="d-flex flex-column gap-1 mb-3">
                                <button type="button" class="btn btn-sm text-start perf-preset-btn {{ $performanceData['preset'] == 'today' ? 'active' : '' }}" data-preset="today">@lang('Today')</button>
                                <button type="button" class="btn btn-sm text-start perf-preset-btn {{ $performanceData['preset'] == 'yesterday' ? 'active' : '' }}" data-preset="yesterday">@lang('Yesterday')</button>
                                <button type="button" class="btn btn-sm text-start perf-preset-btn {{ $performanceData['preset'] == 'last_7_days' ? 'active' : '' }}" data-preset="last_7_days">@lang('Last 7 Days')</button>
                                <button type="button" class="btn btn-sm text-start perf-preset-btn {{ $performanceData['preset'] == 'last_30_days' ? 'active' : '' }}" data-preset="last_30_days">@lang('Last 30 Days')</button>
                                <button type="button" class="btn btn-sm text-start perf-preset-btn {{ $performanceData['preset'] == 'this_month' ? 'active' : '' }}" data-preset="this_month">@lang('This Month')</button>
                                <button type="button" class="btn btn-sm text-start perf-preset-btn {{ $performanceData['preset'] == 'last_month' ? 'active' : '' }}" data-preset="last_month">@lang('Last Month')</button>
                                <button type="button" class="btn btn-sm text-start perf-preset-btn {{ $performanceData['preset'] == 'custom' ? 'active' : '' }}" data-preset="custom">@lang('Custom')</button>
                            </div>

                            <div id="customDateContainer" class="mb-3" style="display: {{ $performanceData['preset'] == 'custom' ? 'block' : 'none' }};">
                                <div class="mb-2">
                                    <label class="form-label small text-muted mb-1">@lang('Start Date')</label>
                                    <input type="date" class="form-control form-control-sm" id="perfStartDate" value="{{ $performanceData['startDate'] }}">
                                </div>
                                <div>
                                    <label class="form-label small text-muted mb-1">@lang('End Date')</label>
                                    <input type="date" class="form-control form-control-sm" id="perfEndDate" value="{{ $performanceData['endDate'] }}">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between gap-2 pt-2 border-top">
                                <button type="button" class="btn btn-sm btn--success flex-grow-1" id="perfApplyBtn">@lang('Submit')</button>
                                <button type="button" class="btn btn-sm btn--dark" id="perfCancelBtn">@lang('Cancel')</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="login-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="row gy-4">
            <div class="col-sm-12">
                <div class="card p-3 rounded-3 overview-widgets-card">
                    <!-- Row 1: Users (3 widgets) -->
                    <div class="row g-0 overview-widget-row border-bottom pb-3 mb-3">
                        <div class="col-4">
                            <div class="dashboard-widget">
                                <div class="dashboard-widget__icon">
                                    <i class="dashboard-card-icon las la-users"></i>
                                </div>
                                <div class="dashboard-widget__content">
                                    <a title="@lang('View all')" class="dashboard-widget-link"
                                        href="{{route('admin.users.all')}}"></a>
                                    <h5 id="widget-total-users">{{$widget['total_users']}}</h5>
                                    <span>@lang('Total Users')</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dashboard-widget">
                                <div class="dashboard-widget__icon">
                                    <i class="dashboard-card-icon las la-user-check"></i>
                                </div>
                                <div class="dashboard-widget__content">
                                    <a title="@lang('View all')" class="dashboard-widget-link"
                                        href="{{route('admin.users.active')}}"></a>
                                    <h5 id="widget-verified-users">{{$widget['verified_users']}}</h5>
                                    <span>@lang('Active Users')</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dashboard-widget">
                                <div class="dashboard-widget__icon">
                                    <i class="dashboard-card-icon las la-envelope"></i>
                                </div>
                                <div class="dashboard-widget__content">
                                    <a title="@lang('View all')" class="dashboard-widget-link"
                                        href="{{route('admin.users.email.unverified')}}"></a>
                                    <h5 id="widget-email-unverified-users">{{$widget['email_unverified_users']}}</h5>
                                    <span>@lang('Email Unverified')</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Catalog (3 widgets) -->
                    <div class="row g-0 overview-widget-row border-bottom pb-3 mb-3">
                        <div class="col-4">
                            <div class="dashboard-widget">
                                <div class="dashboard-widget__icon">
                                    <i class="dashboard-card-icon las la-percentage"></i>
                                </div>
                                <div class="dashboard-widget__content">
                                    <a title="@lang('View all')" class="dashboard-widget-link"
                                        href="{{route('admin.coupon.index')}}"></a>
                                    <h5 id="widget-total-coupons">{{$widget['totalCoupon']}}</h5>
                                    <span>@lang('Total Coupons')</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dashboard-widget">
                                <div class="dashboard-widget__icon">
                                    <i class="dashboard-card-icon las la-list"></i>
                                </div>
                                <div class="dashboard-widget__content">
                                    <a title="@lang('View all')" class="dashboard-widget-link"
                                        href="{{route('admin.category.index')}}"></a>
                                    <h5 id="widget-total-categories">{{$widget['totalCategory']}}</h5>
                                    <span>@lang('Total Categories')</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dashboard-widget">
                                <div class="dashboard-widget__icon">
                                    <i class="dashboard-card-icon las la-store"></i>
                                </div>
                                <div class="dashboard-widget__content">
                                    <a title="@lang('View all')" class="dashboard-widget-link"
                                        href="{{route('admin.store.index')}}"></a>
                                    <h5 id="widget-total-stores">{{$widget['totalStore']}}</h5>
                                    <span>@lang('Total Stores')</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 3: Traffic & Orders (3 widgets) -->
                    <div class="row g-0 overview-widget-row border-bottom pb-3 mb-3">
                        <div class="col-4">
                            <div class="dashboard-widget">
                                <div class="dashboard-widget__icon">
                                    <i class="dashboard-card-icon las la-mouse-pointer"></i>
                                </div>
                                <div class="dashboard-widget__content">
                                    <a title="@lang('View all')" class="dashboard-widget-link"
                                        href="{{route('admin.orders.index')}}"></a>
                                    <h5 id="widget-total-clicks">{{$widget['total_clicks'] ?? 0}}</h5>
                                    <span>@lang('Total Clicks')</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dashboard-widget">
                                <div class="dashboard-widget__icon">
                                    <i class="dashboard-card-icon las la-shopping-basket"></i>
                                </div>
                                <div class="dashboard-widget__content">
                                    <a title="@lang('View all')" class="dashboard-widget-link"
                                        href="{{route('admin.orders.index')}}"></a>
                                    <h5 id="widget-total-orders">{{$widget['total_orders'] ?? 0}}</h5>
                                    <span>@lang('Tracked Orders')</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dashboard-widget">
                                <div class="dashboard-widget__icon">
                                    <i class="dashboard-card-icon las la-check-circle"></i>
                                </div>
                                <div class="dashboard-widget__content">
                                    <a title="@lang('View all')" class="dashboard-widget-link"
                                        href="{{route('admin.orders.index')}}?status=confirmed"></a>
                                    <h5 id="widget-total-conversions">{{$widget['total_conversions'] ?? 0}}</h5>
                                    <span>@lang('Total Conversions')</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 4: Engagement & Finance (3 widgets) -->
                    <div class="row g-0 overview-widget-row">
                        <div class="col-4">
                            <div class="dashboard-widget">
                                <div class="dashboard-widget__icon">
                                    <i class="dashboard-card-icon las la-user-clock"></i>
                                </div>
                                <div class="dashboard-widget__content">
                                    <a title="@lang('View all')" class="dashboard-widget-link"
                                        href="{{route('admin.report.login.history')}}"></a>
                                    <h5 id="widget-total-logins">{{$widget['total_logins'] ?? 0}}</h5>
                                    <span>@lang('Total Logins')</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dashboard-widget">
                                <div class="dashboard-widget__icon">
                                    <i class="dashboard-card-icon las la-eye"></i>
                                </div>
                                <div class="dashboard-widget__content">
                                    <a title="@lang('View all')" class="dashboard-widget-link"
                                        href="javascript:void(0)"></a>
                                    <h5 id="widget-total-impressions">{{formatShortNumber($widget['total_impressions'] ?? 0)}}</h5>
                                    <span>@lang('Impressions')</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="dashboard-widget">
                                <div class="dashboard-widget__icon">
                                    <i class="dashboard-card-icon las la-wallet"></i>
                                </div>
                                <div class="dashboard-widget__content">
                                    <a title="@lang('View all')" class="dashboard-widget-link"
                                        href="{{route('admin.payouts.index')}}"></a>
                                    <h5 id="widget-total-payout">₹ {{formatShortNumber($widget['total_payout_amount'] ?? 0)}}</h5>
                                    <span>@lang('Total Payout')</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title">@lang('Recent Tickets')</h5>
                    <a href="{{route('admin.ticket.pending')}}" class="float-end" target="_blank">@lang('View all')</a>
                </div>
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light">
                        <thead>
                            <tr>
                                <th>@lang('Subject')</th>
                                <th>@lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($newTickets as $item)
                            <tr>
                                <td>
                                    <a class="" href="{{ route('admin.ticket.view', $item->id) }}" class="fw-bold">
                                        @lang('Ticket')#{{ $item->ticket }} - {{ strLimit($item->subject,30) }} </a>
                                </td>
                                <td>
                                    @php echo $item->statusBadge; @endphp
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Top Publishers Section -->
    <div class="col-xl-12">
        <div class="card shadow-sm border b-radius--10 mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="card-title mb-0" style="font-size: 16px; font-weight: 700; color: #1e293b;">@lang('Top Publishers')</h5>
                    <div class="top-pubs-toggle-group btn-group" role="group">
                        <button type="button" class="btn btn-sm top-pubs-toggle-btn active" id="btnViewTable" data-view="table">
                            <i class="las la-list"></i> @lang('Table')
                        </button>
                        <button type="button" class="btn btn-sm top-pubs-toggle-btn" id="btnViewChart" data-view="chart">
                            <i class="las la-chart-pie"></i> @lang('Chart')
                        </button>
                    </div>
                </div>

                <!-- Period Tabs: Today | Yesterday | MTD -->
                <div class="top-pubs-tabs mb-3 d-flex align-items-center gap-2">
                    <button type="button" class="top-pubs-tab-btn active" data-period="today">@lang('Today')</button>
                    <button type="button" class="top-pubs-tab-btn" data-period="yesterday">@lang('Yesterday')</button>
                    <button type="button" class="top-pubs-tab-btn" data-period="mtd">@lang('MTD')</button>
                </div>

                <!-- Table View -->
                <div id="topPublishersTableView">
                    <div class="table-responsive">
                        <table class="table align-middle top-pubs-table mb-0">
                            <thead>
                                <tr>
                                    <th class="text-start">@lang('Publisher')</th>
                                    <th class="text-center">@lang('Clicks')</th>
                                    <th class="text-center">@lang('Impressions')</th>
                                    <th class="text-center">@lang('Conversions')</th>
                                    <th class="text-end">@lang('Payout')</th>
                                </tr>
                            </thead>
                            <tbody id="topPublishersTableBody">
                                @forelse($topPublishers['today'] as $pub)
                                <tr>
                                    <td class="text-start">
                                        <a href="{{ $pub['url'] }}" class="top-pub-link">{{ $pub['name'] }}</a>
                                    </td>
                                    <td class="text-center fw-bold">{{ $pub['clicks_formatted'] }}</td>
                                    <td class="text-center text-muted">{{ $pub['impressions'] }}</td>
                                    <td class="text-center fw-bold text-danger">{{ $pub['conversions'] }}</td>
                                    <td class="text-end fw-bold text-success">{{ $pub['payout_formatted'] }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">@lang('No publisher activity recorded for today')</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Chart View -->
                <div id="topPublishersChartView" style="display: none;">
                    <div id="top-publishers-apex-chart" style="min-height: 330px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')

<script src="{{asset('assets/admin/js/apexcharts.min.js')}}"></script>

<script>
    "use strict";

    // [ login-chart ] start
    (function () {
        var perfData = @json($performanceData);
        var selectedPreset = perfData.preset || 'last_7_days';

        var options = {
            series: [
                {
                    name: "Clicks",
                    data: perfData.clicks
                },
                {
                    name: "Conversions",
                    data: perfData.conversions
                },
                {
                    name: "Impressions",
                    data: perfData.impressions
                },
                {
                    name: "Payout",
                    data: perfData.payout
                },
                {
                    name: "Unique Logins",
                    data: perfData.logins
                }
            ],
            chart: {
                height: '310px',
                type: 'area',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            colors: ['#00adad', '#ea5455', '#5b69bc', '#ff9f43', '#28c76f'],
            labels: perfData.dates,
            xaxis: {
                type: 'date',
            },
            yaxis: {
                opposite: false
            },
            legend: {
                horizontalAlign: 'left'
            }
        };

        var chart = new ApexCharts(document.querySelector("#login-chart"), options);
        chart.render();

        // Date Range interactions
        var dateBtn = $('#perfDateRangeBtn');
        var dropdown = $('#perfDateDropdown');
        var customContainer = $('#customDateContainer');
        var applyBtn = $('#perfApplyBtn');
        var cancelBtn = $('#perfCancelBtn');

        dateBtn.on('click', function (e) {
            e.stopPropagation();
            dropdown.toggle();
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#perfDateRangeBtn, #perfDateDropdown').length) {
                dropdown.hide();
            }
        });

        cancelBtn.on('click', function () {
            dropdown.hide();
        });

        $('.perf-preset-btn').on('click', function () {
            $('.perf-preset-btn').removeClass('active');
            $(this).addClass('active');
            selectedPreset = $(this).data('preset');

            if (selectedPreset === 'custom') {
                customContainer.slideDown(150);
            } else {
                customContainer.slideUp(150);
            }
        });

        applyBtn.on('click', function () {
            var startDate = $('#perfStartDate').val();
            var endDate = $('#perfEndDate').val();
            dropdown.hide();

            $.ajax({
                url: "{{ route('admin.dashboard.performance.chart') }}",
                type: 'GET',
                data: {
                    preset: selectedPreset,
                    start_date: startDate,
                    end_date: endDate
                },
                success: function (res) {
                    if (res && res.dates) {
                        $('#selectedRangeText').text(res.rangeLabel);

                        chart.updateOptions({
                            labels: res.dates,
                            xaxis: {
                                categories: res.dates
                            }
                        });

                        chart.updateSeries([
                            { name: "Clicks", data: res.clicks },
                            { name: "Conversions", data: res.conversions },
                            { name: "Impressions", data: res.impressions },
                            { name: "Payout", data: res.payout },
                            { name: "Unique Logins", data: res.logins }
                        ]);
                    }
                }
            });
        });

        // Smoothly update KPI text with subtle animation if value has changed
        function updateSmoothVal(selector, newVal) {
            var $el = $(selector);
            if ($el.length && $el.text().trim() !== String(newVal).trim()) {
                $el.addClass('kpi-val-updating');
                setTimeout(function() {
                    $el.text(newVal);
                    $el.removeClass('kpi-val-updating').addClass('kpi-val-updated');
                    setTimeout(function() {
                        $el.removeClass('kpi-val-updated');
                    }, 1400);
                }, 120);
            }
        }

        // Realtime Polling (Every 3 seconds without page reload)
        var isPolling = false;
        function pollRealtimeDashboard() {
            if (isPolling) {
                return;
            }
            isPolling = true;

            var startDate = (selectedPreset === 'custom') ? $('#perfStartDate').val() : null;
            var endDate = (selectedPreset === 'custom') ? $('#perfEndDate').val() : null;

            $.ajax({
                url: "{{ route('admin.dashboard.realtime.metrics') }}",
                type: 'GET',
                data: {
                    preset: selectedPreset,
                    start_date: startDate,
                    end_date: endDate
                },
                dataType: 'json',
                success: function (res) {
                    if (res && res.kpi) {
                        // Clicks
                        updateSmoothVal('#kpi-clicks-today', res.kpi.clicks.today);
                        updateSmoothVal('#kpi-clicks-yesterday', res.kpi.clicks.yesterday);
                        updateSmoothVal('#kpi-clicks-mtd', res.kpi.clicks.mtd);

                        // Conversions
                        if (res.kpi.conversions) {
                            updateSmoothVal('#kpi-conversions-today', res.kpi.conversions.today);
                            updateSmoothVal('#kpi-conversions-yesterday', res.kpi.conversions.yesterday);
                            updateSmoothVal('#kpi-conversions-mtd', res.kpi.conversions.mtd);
                            updateSmoothVal('#kpi-cr-today', 'CR: ' + res.kpi.conversions.cr_today);
                            updateSmoothVal('#kpi-cr-yesterday', 'CR: ' + res.kpi.conversions.cr_yesterday);
                            updateSmoothVal('#kpi-cr-mtd', 'CR: ' + res.kpi.conversions.cr_mtd);
                        }

                        // Impressions
                        updateSmoothVal('#kpi-impressions-today', res.kpi.impressions.today);
                        updateSmoothVal('#kpi-impressions-yesterday', res.kpi.impressions.yesterday);
                        updateSmoothVal('#kpi-impressions-mtd', res.kpi.impressions.mtd);

                        // Payout
                        updateSmoothVal('#kpi-payout-today-usd', res.kpi.payout.today_usd);
                        updateSmoothVal('#kpi-payout-today-inr', res.kpi.payout.today_inr);
                        updateSmoothVal('#kpi-payout-yesterday-usd', res.kpi.payout.yesterday_usd);
                        updateSmoothVal('#kpi-payout-yesterday-inr', res.kpi.payout.yesterday_inr);
                        updateSmoothVal('#kpi-payout-mtd-usd', res.kpi.payout.mtd_usd);
                        updateSmoothVal('#kpi-payout-mtd-inr', res.kpi.payout.mtd_inr);

                        // Logins
                        if (res.kpi.logins) {
                            updateSmoothVal('#kpi-logins-today', res.kpi.logins.today);
                            updateSmoothVal('#kpi-logins-yesterday', res.kpi.logins.yesterday);
                            updateSmoothVal('#kpi-logins-mtd', res.kpi.logins.mtd);
                        }
                    }

                    // Smooth widgets update
                    if (res && res.widgets) {
                        updateSmoothVal('#widget-total-users', res.widgets.total_users);
                        updateSmoothVal('#widget-verified-users', res.widgets.verified_users);
                        updateSmoothVal('#widget-email-unverified-users', res.widgets.email_unverified_users);
                        updateSmoothVal('#widget-total-coupons', res.widgets.totalCoupon);
                        updateSmoothVal('#widget-total-categories', res.widgets.totalCategory);
                        updateSmoothVal('#widget-total-stores', res.widgets.totalStore);
                        if (res.widgets.total_conversions !== undefined) {
                            updateSmoothVal('#widget-total-conversions', res.widgets.total_conversions);
                        }
                        if (res.widgets.total_orders !== undefined) {
                            updateSmoothVal('#widget-total-orders', res.widgets.total_orders);
                        }
                        if (res.widgets.total_clicks !== undefined) {
                            updateSmoothVal('#widget-total-clicks', res.widgets.total_clicks);
                        }
                        if (res.widgets.total_logins !== undefined) {
                            updateSmoothVal('#widget-total-logins', res.widgets.total_logins);
                        }
                        if (res.widgets.total_impressions !== undefined) {
                            updateSmoothVal('#widget-total-impressions', res.widgets.total_impressions);
                        }
                        if (res.widgets.total_payout_amount !== undefined) {
                            updateSmoothVal('#widget-total-payout', res.widgets.total_payout_amount);
                        }
                        if (res.top_publishers && typeof window.updateTopPublishersLive === 'function') {
                            window.updateTopPublishersLive(res.top_publishers);
                        }
                    }

                    // Smooth chart update
                    if (res && res.chart && res.chart.dates) {
                        chart.updateSeries([
                            { name: "Clicks", data: res.chart.clicks },
                            { name: "Conversions", data: res.chart.conversions },
                            { name: "Impressions", data: res.chart.impressions },
                            { name: "Payout", data: res.chart.payout },
                            { name: "Unique Logins", data: res.chart.logins }
                        ], true);
                    }
                },
                complete: function () {
                    isPolling = false;
                }
            });
        }

        // Poll every 3 seconds for fast, smooth live updates
        setInterval(pollRealtimeDashboard, 3000);

        // Immediate poll on window focus or visibility change
        $(window).on('focus', pollRealtimeDashboard);
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                pollRealtimeDashboard();
            }
        });
    }) ();

    // [ Top Publishers Handler ]
    (function () {
        var topPubsData = @json($topPublishers);
        var currentPeriod = 'today';
        var currentView = 'table';
        var topPubsChart = null;

        function renderTopPubsTable(period) {
            var pubs = topPubsData[period] || [];
            var html = '';
            if (pubs.length === 0) {
                html = '<tr><td colspan="5" class="text-center py-4 text-muted">No publisher activity recorded for ' + period.toUpperCase() + '</td></tr>';
            } else {
                pubs.forEach(function (pub) {
                    html += '<tr>' +
                        '<td class="text-start"><a href="' + pub.url + '" class="top-pub-link">' + $('<div>').text(pub.name).html() + '</a></td>' +
                        '<td class="text-center fw-bold">' + pub.clicks_formatted + '</td>' +
                        '<td class="text-center text-muted">' + pub.impressions + '</td>' +
                        '<td class="text-center fw-bold text-danger">' + pub.conversions + '</td>' +
                        '<td class="text-end fw-bold text-success">' + pub.payout_formatted + '</td>' +
                        '</tr>';
                });
            }
            $('#topPublishersTableBody').html(html);
        }

        function initOrUpdateChart(period) {
            var pubs = topPubsData[period] || [];
            var categories = [];
            var clicksSeries = [];
            var convSeries = [];
            var payoutSeries = [];

            pubs.forEach(function (pub) {
                categories.push(pub.raw_name || pub.name);
                clicksSeries.push(pub.clicks);
                convSeries.push(pub.conversions);
                payoutSeries.push(pub.payout);
            });

            if (!topPubsChart) {
                var chartOptions = {
                    series: [
                        { name: 'Clicks', data: clicksSeries },
                        { name: 'Conversions', data: convSeries },
                        { name: 'Payout (₹)', data: payoutSeries }
                    ],
                    chart: {
                        type: 'bar',
                        height: 330,
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            borderRadius: 4
                        }
                    },
                    dataLabels: { enabled: false },
                    stroke: { show: true, width: 2, colors: ['transparent'] },
                    xaxis: { categories: categories },
                    yaxis: { title: { text: 'Metrics' } },
                    fill: { opacity: 1 },
                    colors: ['#00adad', '#28c76f', '#ff9f43'],
                    tooltip: {
                        y: {
                            formatter: function (val, opt) {
                                if (opt.seriesIndex === 2) {
                                    return '₹ ' + Number(val).toLocaleString();
                                }
                                return Number(val).toLocaleString();
                            }
                        }
                    }
                };
                topPubsChart = new ApexCharts(document.querySelector("#top-publishers-apex-chart"), chartOptions);
                topPubsChart.render();
            } else {
                topPubsChart.updateOptions({
                    xaxis: { categories: categories }
                });
                topPubsChart.updateSeries([
                    { name: 'Clicks', data: clicksSeries },
                    { name: 'Conversions', data: convSeries },
                    { name: 'Payout (₹)', data: payoutSeries }
                ]);
            }
        }

        // Tab switching: Today | Yesterday | MTD
        $(document).on('click', '.top-pubs-tab-btn', function () {
            $('.top-pubs-tab-btn').removeClass('active');
            $(this).addClass('active');
            currentPeriod = $(this).data('period');

            renderTopPubsTable(currentPeriod);
            if (currentView === 'chart') {
                initOrUpdateChart(currentPeriod);
            }
        });

        // View toggle: Table vs Chart
        $(document).on('click', '.top-pubs-toggle-btn', function () {
            var view = $(this).data('view');
            $('.top-pubs-toggle-btn').removeClass('active');
            $(this).addClass('active');
            currentView = view;

            if (view === 'table') {
                $('#topPublishersChartView').hide();
                $('#topPublishersTableView').fadeIn(150);
            } else {
                $('#topPublishersTableView').hide();
                $('#topPublishersChartView').fadeIn(150);
                setTimeout(function () {
                    initOrUpdateChart(currentPeriod);
                }, 50);
            }
        });

        window.updateTopPublishersLive = function(newPubsData) {
            if (newPubsData) {
                topPubsData = newPubsData;
                renderTopPubsTable(currentPeriod);
                if (currentView === 'chart') {
                    initOrUpdateChart(currentPeriod);
                }
            }
        };
    })();
</script>
@endpush

@push('style')
<style>
.perf-preset-btn {
    background-color: #f8fafc;
    color: #475569;
    font-size: 13px;
    font-weight: 500;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    padding: 5px 10px;
    width: 100%;
}
.perf-preset-btn:hover {
    background-color: #edf2f7;
    color: #1e293b;
}
.perf-preset-btn.active {
    background-color: #00adad !important;
    border-color: #00adad !important;
    color: #ffffff !important;
}
.perf-dropdown-menu {
    border-color: #e2e8f0;
}
.kpi-metric-card {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    padding: 20px 20px 22px 20px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.kpi-metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.07);
}
.kpi-header-title {
    font-size: 16.5px;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.2px;
    white-space: nowrap;
}
.kpi-icon-box {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.kpi-icon-clicks {
    background: #e0f2fe;
    color: #0284c7;
}
.kpi-icon-conversions {
    background: #fee2e2;
    color: #ef4444;
}
.kpi-icon-impressions {
    background: #fef3c7;
    color: #d97706;
}
.kpi-icon-payout {
    background: #dcfce7;
    color: #16a34a;
}
.kpi-icon-logins {
    background: #ede9fe;
    color: #7c3aed;
}
.kpi-badge-link {
    font-size: 11.5px;
    font-weight: 600;
    color: #0284c7;
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 6px;
    padding: 3px 7px;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    white-space: nowrap;
}
.kpi-badge-link:hover {
    background: #e0f2fe;
    color: #0369a1;
}
.kpi-badge-tag {
    font-size: 11.5px;
    font-weight: 600;
    color: #64748b;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 3px 7px;
    white-space: nowrap;
}
.kpi-stat-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #edf2f7;
    padding: 12px 8px;
}
.kpi-stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 2px 2px;
    min-width: 0;
}
.kpi-stat-item:not(:last-child) {
    border-right: 1px solid #e2e8f0;
}
.kpi-stat-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    color: #64748b;
    margin-bottom: 5px;
    white-space: nowrap;
}
.overview-widgets-card {
    border: 1px solid #e9ecef;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
    padding: 16px 10px !important;
}
.overview-widget-row {
    margin-left: 0 !important;
    margin-right: 0 !important;
}
.overview-widget-row > div[class*='col'] {
    padding-left: 2px !important;
    padding-right: 2px !important;
}
.overview-widget-row .dashboard-widget {
    display: flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    align-items: center !important;
    justify-content: flex-start !important;
    position: relative !important;
    padding: 4px 2px !important;
    height: auto !important;
    border: none !important;
    min-width: 0 !important;
    overflow: hidden !important;
}
.overview-widget-row .dashboard-widget__icon {
    width: 26px !important;
    height: 26px !important;
    min-width: 26px !important;
    max-width: 26px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 22px !important;
    color: var(--primary) !important;
    flex-shrink: 0 !important;
}
.overview-widget-row .dashboard-widget__content {
    display: inline-flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    align-items: baseline !important;
    justify-content: flex-start !important;
    gap: 4px !important;
    margin-left: 5px !important;
    width: auto !important;
    text-align: left !important;
    min-width: 0 !important;
    white-space: nowrap !important;
    flex-shrink: 1 !important;
}
.overview-widget-row .dashboard-widget__content h5 {
    display: inline-block !important;
    font-size: 14.5px !important;
    font-weight: 700 !important;
    color: #0f172a !important;
    margin: 0 !important;
    line-height: 1 !important;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}
.overview-widget-row .dashboard-widget__content span {
    font-size: 11.5px !important;
    font-weight: 500 !important;
    color: #64748b !important;
    margin: 0 !important;
    line-height: 1 !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}
.kpi-stat-val,
.kpi-stat-sub,
#widget-total-users,
#widget-verified-users,
#widget-email-unverified-users,
#widget-total-coupons,
#widget-total-categories,
#widget-total-stores,
#widget-total-conversions,
#widget-total-orders,
#widget-total-clicks,
#widget-total-logins,
#widget-total-impressions,
#widget-total-payout {
    display: inline-block;
    transition: opacity 0.2s ease, transform 0.2s ease, color 0.3s ease;
}
.kpi-stat-val {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.25;
    white-space: nowrap;
}
.kpi-stat-sub {
    font-size: 11.5px;
    font-weight: 600;
    color: #64748b;
    margin-top: 3px;
    line-height: 1.15;
    white-space: nowrap;
}
.kpi-val-updating {
    opacity: 0.35;
    transform: scale(0.94);
}
.kpi-val-updated {
    animation: kpiValPulse 1.4s ease;
}
@keyframes kpiValPulse {
    0% {
        transform: scale(1.15);
        color: #00adad !important;
        text-shadow: 0 0 10px rgba(0, 173, 173, 0.45);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
        text-shadow: none;
    }
}
.top-pubs-toggle-group {
    border-radius: 6px;
    overflow: hidden;
    border: 1px solid #1e293b;
    display: inline-flex;
}
.top-pubs-toggle-btn {
    font-size: 12.5px;
    font-weight: 600;
    padding: 6px 16px;
    border: none;
    background: #1e293b;
    color: #ffffff;
    transition: all 0.2s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.top-pubs-toggle-btn.active {
    background: #00a65a !important;
    color: #ffffff !important;
}
.top-pubs-toggle-btn:hover:not(.active) {
    background: #334155;
    color: #ffffff;
}
.top-pubs-tabs {
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 10px;
}
.top-pubs-tab-btn {
    background: transparent;
    border: none;
    padding: 6px 18px;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    border-radius: 4px;
    transition: all 0.2s ease;
    cursor: pointer;
}
.top-pubs-tab-btn:hover {
    color: #0f172a;
    background: #f1f5f9;
}
.top-pubs-tab-btn.active {
    background: #00a65a !important;
    color: #ffffff !important;
}
.top-pubs-table {
    border: 1px solid #edf2f7;
    border-radius: 6px;
    overflow: hidden;
}
.top-pubs-table thead tr th {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    background: #f8fafc;
    border-bottom: 2px solid #edf2f7;
    padding: 12px 18px;
}
.top-pubs-table tbody tr td {
    font-size: 13.5px;
    color: #334155;
    padding: 13px 18px;
    border-bottom: 1px solid #f1f5f9;
}
.top-pubs-table tbody tr:nth-child(even) td {
    background: #fafbfc;
}
.top-pubs-table tbody tr:hover td {
    background: #f1f5f9;
}
.top-pub-link {
    color: #0284c7 !important;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.15s ease;
}
.top-pub-link:hover {
    color: #0369a1 !important;
    text-decoration: underline;
}
</style>
@endpush