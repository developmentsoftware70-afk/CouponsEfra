@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="account-form">
    <div class="col-md-12">

        {{-- Top Balance & Cycle Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="payout-stat-card card-balance">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="stat-label">@lang('Available Cashback')</span>
                        <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                    <h3 class="stat-value text-dark mb-1">₹{{ showAmount($availableBalance) }}</h3>
                    <small class="text-muted fs-12">@lang('Ready to request for payout')</small>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="payout-stat-card card-confirmed">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="stat-label">@lang('Total Confirmed Earnings')</span>
                        <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                    <h3 class="stat-value text-primary mb-1">₹{{ showAmount($totalConfirmedCashback) }}</h3>
                    <small class="text-muted fs-12">@lang('Verified cashback from merchants')</small>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="payout-stat-card card-cycle border-start border-4 border-info">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="stat-label text-info fw-bold"><i class="fas fa-calendar-alt me-1"></i> @lang('Payment Cycle Policy')</span>
                        <span class="badge bg-info bg-opacity-10 text-info fs-11">Net-45</span>
                    </div>
                    <h6 class="fs-14 fw-bold text-dark mb-1">
                        {{ $currentMonthCycle }} @lang('Payout')
                    </h6>
                    <p class="text-muted fs-12 mb-0">
                        <i class="fas fa-info-circle text-info me-1"></i> @lang('October earnings are disbursed by merchants and paid after') <strong>{{ showDateTime($currentPayableDate, 'd M, Y') }}</strong>.
                    </p>
                </div>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="order-tracker-card mb-4">
            <div class="order-tracker-card__header d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="order-header-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div>
                        <h5 class="order-tracker-title mb-0">@lang('Payout & Withdrawal Requests')</h5>
                        <p class="text-muted fs-13 mb-0">@lang('Request bank transfer or UPI disbursement for your verified cashback')</p>
                    </div>
                </div>

                <div>
                    <button type="button" class="btn btn--base btn-sm" data-bs-toggle="modal" data-bs-target="#requestPayoutModal">
                        <i class="fas fa-plus-circle me-1"></i> @lang('Request Payout')
                    </button>
                </div>
            </div>

            <div class="order-tracker-card__body p-0">
                <div class="table-responsive">
                    <table class="table modern-order-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>@lang('Request ID')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Payment Method & Details')</th>
                                <th>@lang('Month Cycle')</th>
                                <th>@lang('Payable Date')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('UTR / Txn ID')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payoutRequests as $item)
                                <tr>
                                    <td><span class="fw-bold text--base">#PR-{{ $item->id }}</span></td>
                                    <td>{{ showDateTime($item->created_at, 'd M, Y h:i A') }}</td>
                                    <td>
                                        <strong class="text-dark fs-15">₹{{ showAmount($item->amount) }}</strong>
                                    </td>
                                    <td>
                                        @if($item->payment_method == 'upi')
                                            <span class="badge bg-dark text-white me-1">UPI</span>
                                            <code class="fw-semibold text-dark">{{ $item->upi_id }}</code>
                                        @else
                                            <div class="fs-12">
                                                <span class="badge bg-primary bg-opacity-10 text-primary mb-1">@lang('Bank')</span>
                                                <strong>{{ $item->bank_name }}</strong> (A/C: {{ Str::mask($item->account_number, '*', 3, -3) }})
                                                <br><small class="text-muted">IFSC: {{ $item->ifsc_code }} | {{ $item->account_holder_name }}</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark fs-13">{{ $item->month_cycle ?? 'Monthly' }}</span>
                                    </td>
                                    <td>
                                        @if($item->eligible_at)
                                            @php
                                                $isEligible = now()->startOfDay()->gte(\Carbon\Carbon::parse($item->eligible_at)->startOfDay());
                                            @endphp
                                            @if($isEligible)
                                                <span class="badge bg-success bg-opacity-10 text-success fs-12">
                                                    <i class="fas fa-check-circle me-1"></i> @lang('Eligible (Since') {{ showDateTime($item->eligible_at, 'd M') }})
                                                </span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning fs-12">
                                                    <i class="fas fa-clock me-1"></i> @lang('After') {{ showDateTime($item->eligible_at, 'd M, Y') }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted fs-12">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $st = strtolower($item->status);
                                            $stClass = match($st) {
                                                'confirmed', 'paid', 'approved' => 'status-confirmed',
                                                'cancelled', 'declined'         => 'status-cancelled',
                                                'under_review', 'unreviewed'    => 'status-processing',
                                                default                         => 'status-pending',
                                            };
                                            $stLabel = match($st) {
                                                'confirmed', 'paid', 'approved' => 'Confirmed',
                                                'cancelled', 'declined'         => 'Cancelled',
                                                'under_review', 'unreviewed'    => 'Under Review',
                                                default                         => 'Pending',
                                            };
                                        @endphp
                                        <span class="modern-status-badge {{ $stClass }}">
                                            <span class="status-indicator-dot"></span>
                                            {{ $stLabel }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->transaction_id)
                                            <code class="fw-bold text-success fs-12">{{ $item->transaction_id }}</code>
                                        @else
                                            <span class="text-muted fs-12">@lang('Awaiting Payment')</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center py-5" colspan="100%">
                                        <div class="empty-order-state py-4 text-center">
                                            <i class="fas fa-hand-holding-usd fs-36 text-muted mb-2 d-block"></i>
                                            <h6 class="text-muted">@lang('No payout requests submitted yet')</h6>
                                            <p class="text-muted fs-13 mb-0">@lang('When your merchant orders are confirmed, you can request payout here.')</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($payoutRequests->hasPages())
                <div class="order-tracker-card__footer p-3 border-top">
                    {{ paginateLinks($payoutRequests) }}
                </div>
            @endif
        </div>

    </div>
</div>

{{-- Request Payout Modal --}}
<div class="modal fade" id="requestPayoutModal" tabindex="-1" aria-labelledby="requestPayoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title fs-16 text-white" id="requestPayoutModalLabel">
                    <i class="fas fa-money-bill-wave me-2 text--base"></i> @lang('Request Payout / Withdrawal')
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.payouts.request') }}" method="POST">
                @csrf
                <div class="modal-body p-4">

                    {{-- Notice Alert --}}
                    <div class="alert alert-warning py-2 px-3 mb-3 fs-12 border-0 bg-warning bg-opacity-10 text-dark">
                        <i class="fas fa-calendar-check text-warning me-1"></i>
                        <strong>@lang('Monthly Cycle Notice'):</strong> @lang('Cashback earned in') <strong>{{ $currentMonthCycle }}</strong> @lang('will be verified and disbursed after') <strong>{{ showDateTime($currentPayableDate, '15 F, Y') }}</strong>.
                    </div>

                    {{-- Amount --}}
                    <div class="form-group mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0">@lang('Withdrawal Amount (₹)') <span class="text-danger">*</span></label>
                            <small class="text-muted">@lang('Available Balance'): <strong class="text-success">₹{{ showAmount($availableBalance) }}</strong></small>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-white fw-bold">₹</span>
                            <input type="number" step="0.01" min="1" name="amount" value="{{ $availableBalance > 0 ? $availableBalance : '' }}" class="form-control" required placeholder="0.00">
                        </div>
                        @if($availableBalance <= 0)
                            <small class="text-muted fs-12 d-block mt-1">
                                <i class="fas fa-info-circle text-primary me-1"></i> @lang('You can submit multiple payout requests. Enter the amount you wish to request.')
                            </small>
                        @endif
                    </div>

                    {{-- Payment Method Selection --}}
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">@lang('Choose Payment Method') <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check flex-fill p-2 border rounded">
                                <input class="form-check-input ms-1 me-2" type="radio" name="payment_method" id="methodBank" value="bank_transfer" checked>
                                <label class="form-check-label fw-semibold" for="methodBank">
                                    <i class="fas fa-university me-1 text-primary"></i> @lang('Bank Transfer')
                                </label>
                            </div>
                            <div class="form-check flex-fill p-2 border rounded">
                                <input class="form-check-input ms-1 me-2" type="radio" name="payment_method" id="methodUPI" value="upi">
                                <label class="form-check-label fw-semibold" for="methodUPI">
                                    <i class="fas fa-mobile-alt me-1 text-success"></i> @lang('UPI ID')
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Bank Details Section --}}
                    <div id="bankDetailsSection">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold fs-13 mb-1">@lang('Account Holder Name') <span class="text-danger">*</span></label>
                            <input type="text" name="account_holder_name" class="form-control" placeholder="@lang('e.g. Rahul Sharma')">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-13 mb-1">@lang('Bank Name') <span class="text-danger">*</span></label>
                                <input type="text" name="bank_name" class="form-control" placeholder="@lang('e.g. State Bank of India')">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-13 mb-1">@lang('IFSC Code') <span class="text-danger">*</span></label>
                                <input type="text" name="ifsc_code" class="form-control" placeholder="@lang('e.g. SBIN0001234')">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label fw-bold fs-13 mb-1">@lang('Bank Account Number') <span class="text-danger">*</span></label>
                            <input type="text" name="account_number" class="form-control" placeholder="@lang('Enter complete account number')">
                        </div>
                    </div>

                    {{-- UPI Section --}}
                    <div id="upiDetailsSection" style="display: none;">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold fs-13 mb-1">@lang('UPI ID / VPA') <span class="text-danger">*</span></label>
                            <input type="text" name="upi_id" class="form-control" placeholder="@lang('e.g. 9876543210@paytm or name@okaxis')">
                            <small class="text-muted fs-12">@lang('Supported: Google Pay, PhonePe, Paytm, BHIM UPI')</small>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn--base btn-sm"><i class="fas fa-check-circle me-1"></i> @lang('Submit Request')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Stat Cards */
.payout-stat-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    border: 1px solid #e2e8f0;
}
.stat-icon-wrapper {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}
.stat-label {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
}
.stat-value {
    font-size: 24px;
    font-weight: 700;
}
.order-tracker-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    overflow: hidden;
}
.order-tracker-card__header {
    padding: 18px 24px;
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
}
.order-header-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}
.modern-order-table th {
    background: #f8fafc;
    color: #475569;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px 18px;
    border-bottom: 1px solid #e2e8f0;
}
.modern-order-table td {
    padding: 16px 18px;
    border-bottom: 1px solid #f1f5f9;
}
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
.status-pending { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
.status-pending .status-indicator-dot { background: #f59e0b; }
.status-confirmed { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.status-confirmed .status-indicator-dot { background: #22c55e; }
.status-processing { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }
.status-processing .status-indicator-dot { background: #8b5cf6; }
.status-cancelled { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
.status-cancelled .status-indicator-dot { background: #ef4444; }
</style>

@push('script')
<script>
    (function ($) {
        "use strict";
        $('input[name="payment_method"]').on('change', function () {
            if ($(this).val() === 'upi') {
                $('#bankDetailsSection').slideUp(150);
                $('#upiDetailsSection').slideDown(150);
                $('#upiDetailsSection input').prop('required', true);
                $('#bankDetailsSection input').prop('required', false);
            } else {
                $('#upiDetailsSection').slideUp(150);
                $('#bankDetailsSection').slideDown(150);
                $('#bankDetailsSection input').prop('required', true);
                $('#upiDetailsSection input').prop('required', false);
            }
        });
    })(jQuery);
</script>
@endpush
@endsection
