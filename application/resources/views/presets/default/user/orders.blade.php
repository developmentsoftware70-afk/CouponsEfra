@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="account-form">
    <div class="col-md-12">
        <div class="order-tracker-card mb-4">
            <div class="order-tracker-card__header d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="order-header-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div>
                        <h5 class="order-tracker-title mb-0">@lang('My Tracked Orders')</h5>
                        <p class="text-muted fs-13 mb-0">@lang('Real-time cashback and purchase status tracking')</p>
                    </div>
                </div>
                <div>
                    <span class="live-status-pill">
                        <span class="live-pulse-dot"></span> @lang('Real-time Sync Active')
                    </span>
                </div>
            </div>

            <div class="order-tracker-card__body p-0">
                <div class="table-responsive">
                    <table class="table modern-order-table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 100px;">@lang('Order ID')</th>
                                <th style="min-width: 280px;">@lang('Product / Store')</th>
                                <th style="width: 140px;">@lang('Amount')</th>
                                <th style="width: 130px;">@lang('Source')</th>
                                <th style="width: 170px;">@lang('Date & Time')</th>
                                <th style="width: 140px;">@lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody id="orders-table-body">
                            @forelse($orders as $order)
                                <tr id="order-row-{{ $order->id }}">
                                    <td>
                                        <span class="order-id-badge">#{{ $order->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="store-avatar-box">
                                                @if($order->image_url)
                                                    <img src="{{ $order->image_url }}" alt="{{ $order->product_name }}" class="store-avatar-img">
                                                @else
                                                    <div class="store-avatar-placeholder">
                                                        <i class="fas fa-store"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="product-info-wrap">
                                                <h6 class="product-info-title mb-1" title="{{ $order->product_name }}">
                                                    {{ Str::limit($order->product_name, 55) }}
                                                </h6>
                                                @if($order->merchant_order_id)
                                                    <span class="text-muted fs-12">
                                                        <i class="fas fa-receipt me-1"></i>Ref: {{ $order->merchant_order_id }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="order-amount-display">
                                            @if($order->amount > 0)
                                                <span class="amount-val fw-bold d-block">{{ showAmount($order->amount) }} {{ $order->currency ?? 'INR' }}</span>
                                                @if($order->payout_amount > 0)
                                                    <span class="badge bg-success bg-opacity-10 text-success fs-11 mt-1">
                                                        <i class="fas fa-coins me-1"></i>+{{ showAmount($order->payout_amount) }} @lang('Cashback')
                                                    </span>
                                                @endif
                                            @else
                                                <span class="amount-pending-text" title="@lang('Amount will update automatically once verified by merchant')">
                                                    @lang('Pending Verify')
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="source-tag">
                                            {{ str_replace('_', ' ', $order->source ?? 'referral') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="date-time-block">
                                            <span class="d-block fw-500 fs-13 text-dark">{{ showDateTime($order->created_at, 'd M, Y') }}</span>
                                            <span class="fs-12 text-muted">{{ showDateTime($order->created_at, 'h:i A') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $status = strtolower($order->status);
                                            $statusMap = [
                                                'confirmed'   => ['class' => 'status-confirmed', 'icon' => 'fa-check-circle', 'label' => 'Confirmed'],
                                                'unreviewed'  => ['class' => 'status-unreviewed', 'icon' => 'fa-eye-slash', 'label' => 'Unreviewed'],
                                                'cancelled'   => ['class' => 'status-cancelled', 'icon' => 'fa-times-circle', 'label' => 'Cancelled'],
                                                'pending'     => ['class' => 'status-pending', 'icon' => 'fa-clock', 'label' => 'Pending'],
                                                'delivered'   => ['class' => 'status-confirmed', 'icon' => 'fa-box-check', 'label' => 'Delivered'],
                                                'shipped'     => ['class' => 'status-shipped', 'icon' => 'fa-shipping-fast', 'label' => 'Shipped'],
                                                'processing'  => ['class' => 'status-processing', 'icon' => 'fa-spinner fa-spin', 'label' => 'Processing'],
                                                'failed'      => ['class' => 'status-cancelled', 'icon' => 'fa-exclamation-circle', 'label' => 'Failed'],
                                            ];
                                            $stInfo = $statusMap[$status] ?? $statusMap['pending'];
                                        @endphp
                                        <span class="modern-status-badge {{ $stInfo['class'] }}" id="order-status-badge-{{ $order->id }}">
                                            <span class="status-indicator-dot"></span>
                                            {{ $stInfo['label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center py-5" colspan="100%">
                                        <div class="empty-order-state">
                                            <div class="empty-icon-box">
                                                <i class="fas fa-shopping-basket"></i>
                                            </div>
                                            <h6 class="mt-3 mb-1">@lang('No orders tracked yet')</h6>
                                            <p class="text-muted fs-13 mb-3">@lang('Click on any store coupon or deal to automatically track your purchase and cashback!')</p>
                                            <a href="{{ route('coupon') }}" class="btn btn--base btn--sm">@lang('Browse Coupons')</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if(method_exists($orders, 'hasPages') && $orders->hasPages())
                <div class="order-tracker-card__footer p-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ paginateLinks($orders) }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
/* Modern Order Tracker Card Styles */
.order-tracker-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e9edf4;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    overflow: hidden;
}

.order-tracker-card__header {
    background: #ffffff;
    padding: 18px 24px;
    border-bottom: 1px solid #edf2f9;
}

.order-header-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: rgba(235, 34, 40, 0.08);
    color: #eb2228;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.order-tracker-title {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
}

.live-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 14px;
    border-radius: 30px;
    background: #ecfdf5;
    color: #059669;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid #a7f3d0;
}

.live-pulse-dot {
    width: 8px;
    height: 8px;
    background-color: #10b981;
    border-radius: 50%;
    animation: pulseRing 1.6s infinite ease-in-out;
}

@keyframes pulseRing {
    0% { transform: scale(0.9); opacity: 0.7; }
    50% { transform: scale(1.35); opacity: 1; }
    100% { transform: scale(0.9); opacity: 0.7; }
}

/* Modern Order Table */
.modern-order-table {
    margin-bottom: 0;
}

.modern-order-table thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px 20px;
    border-bottom: 1px solid #edf2f9;
}

.modern-order-table tbody td {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.modern-order-table tbody tr:hover {
    background-color: #fafbfd;
}

.order-id-badge {
    font-weight: 700;
    font-size: 14px;
    color: #eb2228;
    background: rgba(235, 34, 40, 0.06);
    padding: 4px 10px;
    border-radius: 6px;
}

/* Store Avatar Box */
.store-avatar-box {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}

.store-avatar-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.store-avatar-placeholder {
    color: #94a3b8;
    font-size: 18px;
}

.product-info-title {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.4;
}

.order-amount-display .amount-val {
    font-size: 15px;
    color: #0f172a;
}

.amount-pending-text {
    font-size: 12px;
    color: #d97706;
    background: #fef3c7;
    padding: 3px 8px;
    border-radius: 4px;
    font-weight: 500;
}

.source-tag {
    display: inline-block;
    padding: 4px 10px;
    background: #f1f5f9;
    color: #475569;
    font-size: 12px;
    font-weight: 500;
    border-radius: 20px;
    text-transform: capitalize;
}

/* Status Badges */
.modern-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-indicator-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.status-pending {
    background: #fffbeb;
    color: #b45309;
    border: 1px solid #fde68a;
}
.status-pending .status-indicator-dot { background: #f59e0b; }

.status-confirmed, .status-delivered {
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
}
.status-confirmed .status-indicator-dot, .status-delivered .status-indicator-dot { background: #22c55e; }

.status-shipped, .status-processing {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}
.status-shipped .status-indicator-dot, .status-processing .status-indicator-dot { background: #3b82f6; }

.status-unreviewed {
    background: #f5f3ff;
    color: #6d28d9;
    border: 1px solid #ddd6fe;
}
.status-unreviewed .status-indicator-dot { background: #8b5cf6; }

.status-cancelled {
    background: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}
.status-cancelled .status-indicator-dot { background: #ef4444; }

.btn-quick-sync {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-quick-sync:hover {
    background: #eb2228;
    color: #fff;
    border-color: #eb2228;
}

/* Empty State */
.empty-icon-box {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}
</style>
@endpush

@push('script')
<script>
    (function () {
        "use strict";

        // Auto background refresh every 8 seconds
        setInterval(function() {
            fetch(window.location.href, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTbody = doc.getElementById('orders-table-body');
                const currentTbody = document.getElementById('orders-table-body');
                if (newTbody && currentTbody && newTbody.innerHTML.trim() !== currentTbody.innerHTML.trim()) {
                    currentTbody.innerHTML = newTbody.innerHTML;
                }
            })
            .catch(() => {});
        }, 8000);
    })();
</script>
@endpush
