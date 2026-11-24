@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="account-form">
    <div class="col-md-12">

        {{-- Clean 3 Stat Cards: Confirmed, Pending & Total --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="simple-stat-box border-confirmed">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="stat-title">@lang('Confirmed Earning')</span>
                        <i class="fas fa-check-circle text-success fs-18"></i>
                    </div>
                    <h2 class="stat-num text-success mb-1">{{ $general->cur_sym }}{{ showAmount($totalEarnings) }}</h2>
                    <small class="text-muted">@lang('Verified cashback added to balance')</small>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="simple-stat-box border-pending">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="stat-title">@lang('Pending Earning')</span>
                        <i class="fas fa-clock text-warning fs-18"></i>
                    </div>
                    <h2 class="stat-num text-warning mb-1">{{ $general->cur_sym }}{{ showAmount($pendingEarnings) }}</h2>
                    <small class="text-muted">@lang('Will be added to balance upon confirmation')</small>
                </div>
            </div>

            <div class="col-lg-4 col-md-12 col-12">
                <div class="simple-stat-box" style="border-left: 4px solid #0053c8;">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="stat-title">@lang('Total Estimated Earning')</span>
                        <i class="fas fa-wallet text-primary fs-18"></i>
                    </div>
                    <h2 class="stat-num text-primary mb-1">{{ $general->cur_sym }}{{ showAmount($totalEarnings + $pendingEarnings) }}</h2>
                    <small class="text-muted">@lang('Confirmed + Pending total sum')</small>
                </div>
            </div>
        </div>

        {{-- Clean History Section --}}
        <div class="history-card">
            {{-- Header with Search and Simple Tabs --}}
            <div class="history-card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fas fa-wallet text--base me-1"></i> @lang('My Earnings History')
                </h5>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    {{-- Filter Tabs --}}
                    <div class="simple-tabs">
                        <a href="{{ route('user.my.earnings', ['status' => 'all', 'search' => $filterSearch]) }}" class="{{ ($filterStatus === 'all' || empty($filterStatus)) ? 'active' : '' }}">
                            @lang('All')
                        </a>
                        <a href="{{ route('user.my.earnings', ['status' => 'confirmed', 'search' => $filterSearch]) }}" class="{{ $filterStatus === 'confirmed' ? 'active' : '' }}">
                            @lang('Confirmed')
                        </a>
                        <a href="{{ route('user.my.earnings', ['status' => 'pending', 'search' => $filterSearch]) }}" class="{{ $filterStatus === 'pending' ? 'active' : '' }}">
                            @lang('Pending')
                        </a>
                    </div>

                    {{-- Search Form --}}
                    <form method="GET" action="{{ route('user.my.earnings') }}" class="d-flex align-items-center gap-1">
                        @if($filterStatus && $filterStatus !== 'all')
                            <input type="hidden" name="status" value="{{ $filterStatus }}">
                        @endif
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="@lang('Search store/order...')" value="{{ $filterSearch ?? '' }}" style="max-width: 200px;">
                        <button type="submit" class="btn btn--base btn-sm py-1 px-3 text-nowrap" style="white-space: nowrap;">@lang('Search')</button>
                        @if($filterSearch)
                            <a href="{{ route('user.my.earnings', ['status' => $filterStatus]) }}" class="btn btn-outline-danger btn-sm py-1 px-2" title="@lang('Clear')">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table simple-history-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>@lang('Order ID')</th>
                            <th>@lang('Store / Brand')</th>
                            <th>@lang('Sale Amount')</th>
                            <th>@lang('Cashback Earning')</th>
                            <th>@lang('Date & Time')</th>
                            <th style="text-align: center;">@lang('Status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($earningsHistory as $item)
                            @php
                                $isItemConfirmed = in_array(strtolower($item->status), ['confirmed', 'delivered', 'approved', 'disbursed']);
                            @endphp
                            <tr>
                                {{-- Order ID --}}
                                <td>
                                    <span class="order-id-tag">{{ $item->ref_id }}</span>
                                </td>

                                {{-- Store / Brand --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if(!empty($item->store_logo))
                                            <div class="store-mini-logo">
                                                <img src="{{ $item->store_logo }}" alt="{{ $item->store_name }}">
                                            </div>
                                        @else
                                            <div class="store-mini-logo">
                                                <i class="fas fa-store text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong class="d-block text-dark fs-13">{{ $item->store_name }}</strong>
                                            <small class="text-muted fs-11" title="{{ $item->product_name }}">{{ Str::limit($item->product_name, 45) }}</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Sale Value --}}
                                <td>
                                    <strong class="text-dark">{{ $general->cur_sym }}{{ showAmount($item->sale_amount) }}</strong>
                                </td>

                                {{-- Cashback Amount --}}
                                <td>
                                    <strong class="fs-14 fw-bold text-dark" style="color: #0f172a !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                                        +{{ $general->cur_sym }}{{ showAmount($item->earning_amount) }}
                                    </strong>
                                </td>

                                {{-- Date --}}
                                <td>
                                    <span class="d-block text-dark fs-12">{{ showDateTime($item->created_at, 'd M, Y') }}</span>
                                    <small class="text-muted fs-11">{{ showDateTime($item->created_at, 'h:i A') }}</small>
                                </td>

                                {{-- Status --}}
                                <td style="text-align: center;">
                                    @if($isItemConfirmed)
                                        <span class="badge badge--success">
                                            <i class="fas fa-check-circle me-1"></i>@lang('Confirmed')
                                        </span>
                                    @else
                                        <span class="badge badge--warning">
                                            <i class="fas fa-clock me-1"></i>@lang('Pending')
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center py-4 text-muted">
                                    <i class="fas fa-wallet fs-24 d-block mb-2 text-muted"></i>
                                    @lang('No earnings found.')
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($earningsHistory->hasPages())
                <div class="d-flex justify-content-end p-3 border-top">
                    {{ paginateLinks($earningsHistory) }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('style')
<style>
/* Clean 2-Card Top Stats */
.simple-stat-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px 22px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}
.simple-stat-box.border-confirmed {
    border-left: 4px solid #10b981;
}
.simple-stat-box.border-pending {
    border-left: 4px solid #f59e0b;
}
.stat-title {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.stat-num {
    font-size: 26px;
    font-weight: 800;
    margin: 4px 0;
}

/* History Card */
.history-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}
.history-card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e2e8f0;
    background: #ffffff;
}

/* Simple Tabs */
.simple-tabs {
    display: inline-flex;
    background: #f1f5f9;
    padding: 3px;
    border-radius: 8px;
}
.simple-tabs a {
    padding: 5px 14px;
    font-size: 12.5px;
    font-weight: 600;
    color: #64748b;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.15s ease;
}
.simple-tabs a.active {
    background: #ffffff;
    color: #0053c8;
    box-shadow: 0 1px 2px rgba(0,0,0,0.06);
}

/* Table */
.simple-history-table th {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    color: #475569;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 18px;
}
.simple-history-table td {
    padding: 13px 18px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
}
.order-id-tag {
    font-weight: 700;
    font-size: 12px;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    color: #eb2228;
    background: rgba(235, 34, 40, 0.06);
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-block;
    white-space: nowrap;
}
.store-mini-logo {
    width: 36px;
    height: 36px;
    min-width: 36px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2px;
}
.store-mini-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

/* Mobile responsive */
@media screen and (max-width: 991px) {
    .simple-history-table thead {
        display: none !important;
    }
    .simple-history-table tbody tr {
        display: block !important;
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        margin: 10px 12px !important;
        padding: 8px 12px !important;
    }
    .simple-history-table tbody tr td {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 6px 0 !important;
        border: none !important;
        border-bottom: 1px dashed #f1f5f9 !important;
    }
    .simple-history-table tbody tr td:last-child {
        border-bottom: none !important;
    }
    .simple-history-table tbody tr td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #64748b;
        font-size: 11px;
        text-transform: uppercase;
    }
}
</style>
@endpush
