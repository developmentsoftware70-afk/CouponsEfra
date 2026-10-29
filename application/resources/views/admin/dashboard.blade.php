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
                <div class="card p-3 rounded-3">
                    <div class="row g-0">
                        <div class="col-sm-4 col-6 col-xl-6 col-xxl-4">
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
                        <div class="col-sm-4 col-6 col-xl-6 col-xxl-4 ">
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
                        <div class="col-sm-4 col-6 col-xl-6 col-xxl-4">
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
                        <div class="col-sm-4 col-6 col-xl-6 col-xxl-4">
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

                        <div class="col-sm-4 col-6 col-xl-6 col-xxl-4">
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
                        <div class="col-sm-4 col-6 col-xl-6 col-xxl-4">
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
                        <div class="col-sm-4 col-6 col-xl-6 col-xxl-4">
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
                        <div class="col-sm-4 col-6 col-xl-6 col-xxl-4">
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
.kpi-stat-val,
.kpi-stat-sub,
#widget-total-users,
#widget-verified-users,
#widget-email-unverified-users,
#widget-total-coupons,
#widget-total-categories,
#widget-total-stores {
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
</style>
@endpush