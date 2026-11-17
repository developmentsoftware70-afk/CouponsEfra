@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="account-form">
    <div class="col-md-12">

        {{-- Top Balance & Cycle Cards (Compact & Clean) --}}
        <div class="row g-3 mb-3">
            <div class="col-xl-3 col-sm-6">
                <div class="compact-stat-card card-balance">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="stat-subtitle">@lang('Available Cashback')</span>
                            <h3 class="stat-num text-dark mb-0">₹{{ showAmount($availableBalance) }}</h3>
                            <small class="text-muted fs-11">@lang('Ready to withdraw')</small>
                        </div>
                        <div class="stat-circle-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="compact-stat-card card-confirmed">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="stat-subtitle">@lang('Confirmed Earnings')</span>
                            <h3 class="stat-num text-success mb-0">₹{{ showAmount($totalConfirmedCashback) }}</h3>
                            <small class="text-muted fs-11">@lang('Verified by merchants')</small>
                        </div>
                        <div class="stat-circle-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="compact-stat-card card-pending">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="stat-subtitle">@lang('Pending Cashback')</span>
                            <h3 class="stat-num text-warning mb-0">₹{{ showAmount($pendingCashback ?? 0) }}</h3>
                            <small class="text-muted fs-11">@lang('Awaiting store confirmation')</small>
                        </div>
                        <div class="stat-circle-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="compact-stat-card card-cycle">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-1 mb-1">
                                <span class="stat-subtitle mb-0">@lang('Payment Cycle')</span>
                                <span class="badge bg-info bg-opacity-10 text-info fs-10 py-0 px-1">Net-45</span>
                            </div>
                            <h6 class="fs-14 fw-bold text-dark mb-0">{{ $currentMonthCycle }}</h6>
                            <small class="text-muted fs-11">
                                @lang('Payable after') <strong>{{ showDateTime($currentPayableDate, 'd M, Y') }}</strong>
                            </small>
                        </div>
                        <div class="stat-circle-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sleek Payout Receiving Account Banner (Optimized Accountability Hub) --}}
        <div class="payout-linked-card mb-3 p-3 bg-white border rounded-3 shadow-sm">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="account-icon-pill bg-primary bg-opacity-10 text-primary">
                        <i class="fas {{ ($user->payout_method ?? '') === 'upi' ? 'fa-mobile-alt' : 'fa-university' }}"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <span class="text-uppercase text-muted fw-bold fs-11" style="letter-spacing: 0.5px;">@lang('Payout Receiving Account')</span>
                            @if(!empty($user->bank_account_number) || !empty($user->upi_id))
                                <span class="badge bg-success bg-opacity-10 text-success fs-11 py-1 px-2">
                                    <i class="fas fa-check-circle me-1"></i>@lang('Active & Verified')
                                </span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning fs-11 py-1 px-2">
                                    <i class="fas fa-exclamation-triangle me-1"></i>@lang('Account Not Set Up')
                                </span>
                            @endif
                        </div>

                        @if(!empty($user->bank_account_number) || !empty($user->upi_id))
                            <div class="d-flex align-items-center gap-2 flex-wrap text-dark fs-13">
                                @if(!empty($user->bank_account_number))
                                    <span class="fw-bold text-dark">
                                        <i class="fas fa-building-columns text-primary me-1"></i>{{ $user->bank_name ?? 'Bank' }}
                                    </span>
                                    <span class="text-muted">|</span>
                                    <span>A/C: <code class="text-dark fw-bold">•••• {{ substr($user->bank_account_number, -4) }}</code></span>
                                    <span class="text-muted">|</span>
                                    <span>IFSC: <code class="text-dark">{{ $user->bank_ifsc }}</code></span>
                                    <span class="text-muted">|</span>
                                    <span>Holder: <strong>{{ $user->bank_account_name }}</strong></span>
                                @endif

                                @if(!empty($user->upi_id))
                                    @if(!empty($user->bank_account_number)) <span class="text-muted">|</span> @endif
                                    <span class="badge bg-dark text-white px-2 py-1 fs-12">
                                        <i class="fas fa-mobile-alt me-1"></i>UPI: {{ $user->upi_id }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <p class="text-muted fs-13 mb-0">
                                @lang('Apna Bank Account ya UPI ID link karein taaki withdrawal request bina kisi delay ke transfer ho sake.')
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Explicit High-Contrast Action Buttons --}}
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn-action-primary" data-bs-toggle="modal" data-bs-target="#updateDefaultAccountModal">
                        <i class="fas fa-edit"></i> @lang('Update Account Details')
                    </button>
                    <button type="button" class="btn-action-success" data-bs-toggle="modal" data-bs-target="#requestPayoutModal">
                        <i class="fas fa-plus-circle"></i> @lang('Request Payout')
                    </button>
                </div>
            </div>
        </div>

        {{-- Payment & Withdrawal History Card --}}
        <div class="order-tracker-card mb-4">
            <div class="order-tracker-card__header d-flex flex-wrap justify-content-between align-items-center gap-2 py-3 px-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="order-header-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <h6 class="order-tracker-title mb-0 fw-bold">@lang('Payment & Withdrawal History')</h6>
                        <small class="text-muted fs-12">@lang('All payout requests, verification cycles, and transaction statuses')</small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-muted border fs-12 px-2 py-1">
                        @lang('Total Requests'): {{ $payoutRequests->total() }}
                    </span>
                </div>
            </div>

            <div class="order-tracker-card__body p-0">
                <div class="table-responsive">
                    <table class="table modern-payout-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="min-width: 130px;">@lang('Request ID & Date')</th>
                                <th style="min-width: 100px;">@lang('Amount')</th>
                                <th style="min-width: 180px;">@lang('Payout Account')</th>
                                <th style="min-width: 150px;">@lang('Payout Cycle')</th>
                                <th style="min-width: 140px;">@lang('Status & UTR')</th>
                                <th style="min-width: 130px; text-align: center;">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payoutRequests as $item)
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
                                <tr>
                                    {{-- 1. Request ID & Date Combined --}}
                                    <td>
                                        <span class="fw-bold text--base text-nowrap">#PR-{{ $item->id }}</span>
                                        <small class="text-muted d-block fs-11 text-nowrap mt-1">
                                            {{ showDateTime($item->created_at, 'd M, Y • h:i A') }}
                                        </small>
                                    </td>

                                    {{-- 2. Amount (Never wraps!) --}}
                                    <td>
                                        <strong class="text-dark fs-15 text-nowrap">₹{{ showAmount($item->amount) }}</strong>
                                    </td>

                                    {{-- 3. Payment Method & Account Details --}}
                                    <td>
                                        @if($item->payment_method == 'upi')
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="badge bg-dark text-white fs-11 px-2 py-1">UPI</span>
                                                <code class="fw-semibold text-dark fs-12 text-nowrap">{{ $item->upi_id }}</code>
                                            </div>
                                        @else
                                            <div>
                                                <div class="text-nowrap fs-12">
                                                    <span class="badge bg-primary bg-opacity-10 text-primary me-1">@lang('Bank')</span>
                                                    <strong>{{ $item->bank_name }}</strong> (••• {{ substr($item->account_number, -4) }})
                                                </div>
                                                <small class="text-muted fs-11 text-nowrap d-block mt-1">
                                                    IFSC: {{ $item->ifsc_code }} | {{ $item->account_holder_name }}
                                                </small>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- 4. Payout Cycle & Payable Date Combined --}}
                                    <td>
                                        <span class="fw-semibold text-dark fs-12 text-nowrap d-block">
                                            {{ $item->month_cycle ?? 'Monthly' }} @lang('Cycle')
                                        </span>
                                        @if($item->eligible_at)
                                            @php
                                                $isEligible = now()->startOfDay()->gte(\Carbon\Carbon::parse($item->eligible_at)->startOfDay());
                                            @endphp
                                            @if($isEligible)
                                                <span class="badge bg-success bg-opacity-10 text-success fs-11 text-nowrap mt-1">
                                                    <i class="fas fa-check-circle me-1"></i>@lang('Eligible (15 Nov)')
                                                </span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning fs-11 text-nowrap mt-1">
                                                    <i class="fas fa-clock me-1"></i>@lang('After') {{ showDateTime($item->eligible_at, 'd M, Y') }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted fs-11">-</span>
                                        @endif
                                    </td>

                                    {{-- 5. Status & Txn/UTR Combined --}}
                                    <td>
                                        <span class="modern-status-badge text-nowrap {{ $stClass }}">
                                            <span class="status-indicator-dot"></span>
                                            {{ $stLabel }}
                                        </span>
                                        @if($item->transaction_id)
                                            <small class="text-muted d-block fs-11 text-nowrap mt-1">
                                                UTR: <code class="fw-bold text-success">{{ $item->transaction_id }}</code>
                                            </small>
                                        @else
                                            <small class="text-muted d-block fs-11 text-nowrap mt-1">
                                                @lang('Awaiting Payment')
                                            </small>
                                        @endif
                                    </td>

                                    {{-- 6. Action Column (Clear visible button) --}}
                                    <td style="text-align: center;">
                                        @if(in_array($st, ['pending', 'under_review', 'unreviewed']))
                                            <button type="button" class="btn-table-edit" data-bs-toggle="modal" data-bs-target="#editPaymentRequestModal{{ $item->id }}" title="@lang('Update Account Details')">
                                                <i class="fas fa-edit"></i> @lang('Update Details')
                                            </button>
                                        @else
                                            <span class="badge bg-light text-muted border text-nowrap px-2 py-1 fs-11">
                                                <i class="fas fa-lock me-1 text-secondary"></i> @lang('Processed')
                                            </span>
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

{{-- Update Default Payout Account Modal (Clean & Compact) --}}
<div class="modal fade" id="updateDefaultAccountModal" tabindex="-1" aria-labelledby="updateDefaultAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title fs-16 text-white" id="updateDefaultAccountModalLabel">
                    <i class="fas fa-university me-2 text--base"></i> @lang('Payout Account Settings')
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.payouts.account.update') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted fs-13 mb-3">
                        @lang('Apna Bank Account ya UPI ID yahan save karein. Future me jab bhi aap withdrawal request karenge, ye details automatic pre-fill hongi.')
                    </p>

                    @php
                        $userDefMethod = $user->payout_method ?? 'bank_transfer';
                    @endphp

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold fs-13 mb-1">@lang('Preferred Payment Method') <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check flex-fill p-2 border rounded">
                                <input class="form-check-input ms-1 me-2 def-method-toggle" type="radio" name="payment_method" id="defaultMethodBank" value="bank_transfer" {{ $userDefMethod === 'bank_transfer' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold fs-13" for="defaultMethodBank">
                                    <i class="fas fa-university me-1 text-primary"></i> @lang('Bank Transfer')
                                </label>
                            </div>
                            <div class="form-check flex-fill p-2 border rounded">
                                <input class="form-check-input ms-1 me-2 def-method-toggle" type="radio" name="payment_method" id="defaultMethodUPI" value="upi" {{ $userDefMethod === 'upi' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold fs-13" for="defaultMethodUPI">
                                    <i class="fas fa-mobile-alt me-1 text-success"></i> @lang('UPI ID')
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Bank Transfer Fields --}}
                    <div id="defaultBankSection" style="display: {{ $userDefMethod === 'bank_transfer' ? 'block' : 'none' }};">
                        <div class="form-group mb-2">
                            <label class="form-label fw-bold fs-12 mb-1">@lang('Account Holder Name') <span class="text-danger">*</span></label>
                            <input type="text" name="account_holder_name" class="form-control" value="{{ $user->bank_account_name ?? ($user->fullname ?? '') }}" placeholder="@lang('e.g. Rahul Sharma')">
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label fw-bold fs-12 mb-1">@lang('Bank Name') <span class="text-danger">*</span></label>
                                <input type="text" name="bank_name" class="form-control" value="{{ $user->bank_name ?? '' }}" placeholder="@lang('e.g. State Bank of India')">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold fs-12 mb-1">@lang('IFSC Code') <span class="text-danger">*</span></label>
                                <input type="text" name="ifsc_code" class="form-control text-uppercase" value="{{ $user->bank_ifsc ?? '' }}" placeholder="@lang('e.g. SBIN0001234')">
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label fw-bold fs-12 mb-1">@lang('Bank Account Number') <span class="text-danger">*</span></label>
                            <input type="text" name="account_number" class="form-control" value="{{ $user->bank_account_number ?? '' }}" placeholder="@lang('Enter complete account number')">
                        </div>
                    </div>

                    {{-- UPI Field --}}
                    <div id="defaultUpiSection" style="display: {{ $userDefMethod === 'upi' ? 'block' : 'none' }};">
                        <div class="form-group mb-2">
                            <label class="form-label fw-bold fs-12 mb-1">@lang('UPI ID / Virtual Address') <span class="text-danger">*</span></label>
                            <input type="text" name="upi_id" class="form-control" value="{{ $user->upi_id ?? '' }}" placeholder="@lang('e.g. 9876543210@paytm or name@okaxis')">
                            <small class="text-muted fs-11">@lang('Supported: Google Pay, PhonePe, Paytm, BHIM UPI')</small>
                        </div>
                    </div>

                    <div class="form-check mt-3 pt-2 border-top">
                        <input class="form-check-input" type="checkbox" name="apply_to_pending" value="1" id="applyToPendingCheck" checked>
                        <label class="form-check-label fs-12 fw-semibold text-dark" for="applyToPendingCheck">
                            <i class="fas fa-sync-alt text-primary me-1"></i> @lang('Apply these new details to all my current pending payout requests as well')
                        </label>
                    </div>

                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn-action-primary"><i class="fas fa-save me-1"></i> @lang('Save Account Details')</button>
                </div>
            </form>
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

                    <div class="alert alert-warning py-2 px-3 mb-3 fs-12 border-0 bg-warning bg-opacity-10 text-dark">
                        <i class="fas fa-calendar-check text-warning me-1"></i>
                        <strong>@lang('Monthly Cycle Notice'):</strong> @lang('Cashback earned in') <strong>{{ $currentMonthCycle }}</strong> @lang('will be verified and disbursed after') <strong>{{ showDateTime($currentPayableDate, '15 F, Y') }}</strong>.
                    </div>

                    <div class="form-group mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0 fs-13">@lang('Withdrawal Amount (₹)') <span class="text-danger">*</span></label>
                            <small class="text-muted">@lang('Available'): <strong class="text-success">₹{{ showAmount($availableBalance) }}</strong></small>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-white fw-bold">₹</span>
                            <input type="number" step="0.01" min="1" name="amount" value="{{ $availableBalance > 0 ? $availableBalance : '' }}" class="form-control" required placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold fs-13 mb-1">@lang('Choose Payment Method') <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check flex-fill p-2 border rounded">
                                <input class="form-check-input ms-1 me-2 req-modal-method-toggle" type="radio" name="payment_method" id="modalMethodBank" value="bank_transfer" {{ ($user->payout_method ?? 'bank_transfer') === 'bank_transfer' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold fs-13" for="modalMethodBank">
                                    <i class="fas fa-university me-1 text-primary"></i> @lang('Bank Transfer')
                                </label>
                            </div>
                            <div class="form-check flex-fill p-2 border rounded">
                                <input class="form-check-input ms-1 me-2 req-modal-method-toggle" type="radio" name="payment_method" id="modalMethodUPI" value="upi" {{ ($user->payout_method ?? '') === 'upi' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold fs-13" for="modalMethodUPI">
                                    <i class="fas fa-mobile-alt me-1 text-success"></i> @lang('UPI ID')
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="modalBankDetailsSection" style="display: {{ ($user->payout_method ?? 'bank_transfer') === 'bank_transfer' ? 'block' : 'none' }};">
                        <div class="form-group mb-2">
                            <label class="form-label fw-bold fs-12 mb-1">@lang('Account Holder Name') <span class="text-danger">*</span></label>
                            <input type="text" name="account_holder_name" class="form-control" value="{{ $user->bank_account_name ?? ($user->fullname ?? '') }}" placeholder="@lang('e.g. Rahul Sharma')">
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label fw-bold fs-12 mb-1">@lang('Bank Name') <span class="text-danger">*</span></label>
                                <input type="text" name="bank_name" class="form-control" value="{{ $user->bank_name ?? '' }}" placeholder="@lang('e.g. State Bank of India')">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold fs-12 mb-1">@lang('IFSC Code') <span class="text-danger">*</span></label>
                                <input type="text" name="ifsc_code" class="form-control text-uppercase" value="{{ $user->bank_ifsc ?? '' }}" placeholder="@lang('e.g. SBIN0001234')">
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label fw-bold fs-12 mb-1">@lang('Bank Account Number') <span class="text-danger">*</span></label>
                            <input type="text" name="account_number" class="form-control" value="{{ $user->bank_account_number ?? '' }}" placeholder="@lang('Enter complete account number')">
                        </div>
                    </div>

                    <div id="modalUpiDetailsSection" style="display: {{ ($user->payout_method ?? '') === 'upi' ? 'block' : 'none' }};">
                        <div class="form-group mb-2">
                            <label class="form-label fw-bold fs-12 mb-1">@lang('UPI ID / VPA') <span class="text-danger">*</span></label>
                            <input type="text" name="upi_id" class="form-control" value="{{ $user->upi_id ?? '' }}" placeholder="@lang('e.g. 9876543210@paytm or name@okaxis')">
                            <small class="text-muted fs-11">@lang('Supported: Google Pay, PhonePe, Paytm, BHIM UPI')</small>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn-action-success"><i class="fas fa-check-circle me-1"></i> @lang('Submit Request')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Individual Pending Payout Request Modals --}}
@foreach($payoutRequests as $item)
    @php
        $itemSt = strtolower($item->status);
    @endphp
    @if(in_array($itemSt, ['pending', 'under_review', 'unreviewed']))
    <div class="modal fade" id="editPaymentRequestModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fs-16 text-white">
                        <i class="fas fa-edit me-2 text-warning"></i> @lang('Update Payout Account') (#PR-{{ $item->id }})
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('user.payouts.request.update', $item->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="alert alert-info py-2 px-3 mb-3 fs-12 border-0 bg-info bg-opacity-10 text-dark">
                            <i class="fas fa-info-circle text-info me-1"></i>
                            @lang('Account me koi problem ya change ho toh yahan se update karein. Admin isi updated account me payment bhejega.')
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label fw-bold fs-13 mb-1">@lang('Payment Method') <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check flex-fill p-2 border rounded">
                                    <input class="form-check-input ms-1 me-2 edit-pay-method-radio" type="radio" name="payment_method" id="editPayMethodBank{{ $item->id }}" value="bank_transfer" {{ $item->payment_method === 'bank_transfer' ? 'checked' : '' }} data-item-id="{{ $item->id }}">
                                    <label class="form-check-label fw-semibold fs-13" for="editPayMethodBank{{ $item->id }}">
                                        <i class="fas fa-university me-1 text-primary"></i> @lang('Bank Transfer')
                                    </label>
                                </div>
                                <div class="form-check flex-fill p-2 border rounded">
                                    <input class="form-check-input ms-1 me-2 edit-pay-method-radio" type="radio" name="payment_method" id="editPayMethodUPI{{ $item->id }}" value="upi" {{ $item->payment_method === 'upi' ? 'checked' : '' }} data-item-id="{{ $item->id }}">
                                    <label class="form-check-label fw-semibold fs-13" for="editPayMethodUPI{{ $item->id }}">
                                        <i class="fas fa-mobile-alt me-1 text-success"></i> @lang('UPI ID')
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="editPayBankSection{{ $item->id }}" style="display: {{ $item->payment_method === 'bank_transfer' ? 'block' : 'none' }};">
                            <div class="form-group mb-2">
                                <label class="form-label fw-bold fs-12 mb-1">@lang('Account Holder Name') <span class="text-danger">*</span></label>
                                <input type="text" name="account_holder_name" class="form-control" value="{{ $item->account_holder_name ?? ($user->bank_account_name ?? ($user->fullname ?? '')) }}" placeholder="@lang('e.g. Rahul Sharma')">
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label fw-bold fs-12 mb-1">@lang('Bank Name') <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_name" class="form-control" value="{{ $item->bank_name ?? ($user->bank_name ?? '') }}" placeholder="@lang('e.g. State Bank of India')">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold fs-12 mb-1">@lang('IFSC Code') <span class="text-danger">*</span></label>
                                    <input type="text" name="ifsc_code" class="form-control text-uppercase" value="{{ $item->ifsc_code ?? ($user->bank_ifsc ?? '') }}" placeholder="@lang('e.g. SBIN0001234')">
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <label class="form-label fw-bold fs-12 mb-1">@lang('Bank Account Number') <span class="text-danger">*</span></label>
                                <input type="text" name="account_number" class="form-control" value="{{ $item->account_number ?? ($user->bank_account_number ?? '') }}" placeholder="@lang('Enter account number')">
                            </div>
                        </div>

                        <div id="editPayUpiSection{{ $item->id }}" style="display: {{ $item->payment_method === 'upi' ? 'block' : 'none' }};">
                            <div class="form-group mb-2">
                                <label class="form-label fw-bold fs-12 mb-1">@lang('UPI ID / VPA') <span class="text-danger">*</span></label>
                                <input type="text" name="upi_id" class="form-control" value="{{ $item->upi_id ?? ($user->upi_id ?? '') }}" placeholder="@lang('e.g. name@paytm or 9876543210@upi')">
                                <small class="text-muted fs-11">@lang('Supported: Google Pay, PhonePe, Paytm, BHIM UPI')</small>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn-action-primary"><i class="fas fa-check-circle me-1"></i> @lang('Save Changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

<style>
/* Clean & Compact Stat Cards */
.compact-stat-card {
    background: #ffffff;
    border-radius: 10px;
    padding: 16px 18px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}
.compact-stat-card:hover {
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
}
.stat-subtitle {
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
    display: block;
}
.stat-num {
    font-size: 20px;
    font-weight: 700;
    white-space: nowrap !important;
}
.stat-circle-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

/* Linked Account Banner */
.payout-linked-card {
    border-left: 4px solid #4f46e5 !important;
    background: #ffffff;
}
.account-icon-pill {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

/* Explicit Button Styling (Prevents white-on-white text) */
.btn-action-primary {
    background-color: #4f46e5 !important;
    color: #ffffff !important;
    border: 1px solid #4f46e5 !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    padding: 8px 16px !important;
    border-radius: 8px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    line-height: 1.4 !important;
    cursor: pointer !important;
    text-decoration: none !important;
    transition: all 0.15s ease-in-out !important;
}
.btn-action-primary:hover {
    background-color: #4338ca !important;
    color: #ffffff !important;
    border-color: #4338ca !important;
}

.btn-action-success {
    background-color: #10b981 !important;
    color: #ffffff !important;
    border: 1px solid #10b981 !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    padding: 8px 16px !important;
    border-radius: 8px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    line-height: 1.4 !important;
    cursor: pointer !important;
    text-decoration: none !important;
    transition: all 0.15s ease-in-out !important;
}
.btn-action-success:hover {
    background-color: #059669 !important;
    color: #ffffff !important;
    border-color: #059669 !important;
}

/* Table Edit Button */
.btn-table-edit {
    background-color: #fef3c7 !important;
    color: #92400e !important;
    border: 1px solid #fcd34d !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    padding: 6px 14px !important;
    border-radius: 6px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 5px !important;
    white-space: nowrap !important;
    cursor: pointer !important;
    text-decoration: none !important;
    transition: all 0.15s ease-in-out !important;
}
.btn-table-edit:hover {
    background-color: #f59e0b !important;
    color: #ffffff !important;
    border-color: #f59e0b !important;
}

/* Modern Payout Table */
.order-tracker-card {
    background: #ffffff;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    overflow: hidden;
}
.order-tracker-card__header {
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
}
.order-header-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
}
.modern-payout-table th {
    background: #f8fafc;
    color: #475569;
    font-size: 11px;
    padding: 12px 14px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
}
.modern-payout-table td {
    padding: 12px 14px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

/* Status Badges (Never wrap) */
.modern-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 16px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap !important;
}
.status-indicator-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
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

        // 1. Default Account Modal Toggle
        $('.def-method-toggle').on('change', function () {
            if ($(this).val() === 'upi') {
                $('#defaultBankSection').slideUp(150);
                $('#defaultUpiSection').slideDown(150);
            } else {
                $('#defaultUpiSection').slideUp(150);
                $('#defaultBankSection').slideDown(150);
            }
        });

        // 2. Request Payout Modal Toggle
        $('.req-modal-method-toggle').on('change', function () {
            if ($(this).val() === 'upi') {
                $('#modalBankDetailsSection').slideUp(150);
                $('#modalUpiDetailsSection').slideDown(150);
            } else {
                $('#modalUpiDetailsSection').slideUp(150);
                $('#modalBankDetailsSection').slideDown(150);
            }
        });

        // 3. Edit Individual Request Modals Toggle
        $('.edit-pay-method-radio').on('change', function () {
            var itemId = $(this).data('item-id');
            if ($(this).val() === 'upi') {
                $('#editPayBankSection' + itemId).slideUp(150);
                $('#editPayUpiSection' + itemId).slideDown(150);
            } else {
                $('#editPayUpiSection' + itemId).slideUp(150);
                $('#editPayBankSection' + itemId).slideDown(150);
            }
        });
    })(jQuery);
</script>
@endpush
@endsection
