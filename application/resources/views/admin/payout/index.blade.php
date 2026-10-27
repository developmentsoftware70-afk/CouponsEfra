@extends('admin.layouts.app')
@section('panel')

{{-- Top Summary Cards (4 Core Statuses: Pending, Confirmed, Under Review, Cancelled) --}}
<div class="row g-3 mb-4">
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--white p-3 b-radius--10 shadow-sm border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1 fs-13">@lang('Pending Requests')</h6>
                    <h3 class="mb-0 text--warning">{{ $pendingCount }}</h3>
                </div>
                <div class="icon-circle bg-warning bg-opacity-10 text-warning p-3 rounded-circle">
                    <i class="las la-clock fs-24"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--white p-3 b-radius--10 shadow-sm border-start border-4 border-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1 fs-13">@lang('Under Review')</h6>
                    <h3 class="mb-0 text--primary">{{ $underReviewCount }}</h3>
                </div>
                <div class="icon-circle bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                    <i class="las la-hourglass-half fs-24"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--white p-3 b-radius--10 shadow-sm border-start border-4 border-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1 fs-13">@lang('Confirmed / Paid')</h6>
                    <h3 class="mb-0 text--success">{{ $confirmedCount }}</h3>
                </div>
                <div class="icon-circle bg-success bg-opacity-10 text-success p-3 rounded-circle">
                    <i class="las la-check-circle fs-24"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--white p-3 b-radius--10 shadow-sm border-start border-4 border-danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1 fs-13">@lang('Cancelled')</h6>
                    <h3 class="mb-0 text--danger">{{ $cancelledCount }}</h3>
                </div>
                <div class="icon-circle bg-danger bg-opacity-10 text-danger p-3 rounded-circle">
                    <i class="las la-times-circle fs-24"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bulk Action Bar --}}
<div class="card b-radius--10 mb-3 shadow-sm border-0" id="bulkActionBar" style="display: none; background: #f8fafc; border-left: 4px solid #4634ff !important;">
    <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg--primary fs-13" id="selectedCountBadge">0 Selected</span>
            <span class="text-muted fs-13">@lang('Apply bulk action to selected payout requests:')</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-outline--primary" id="btnBulkReview">
                <i class="las la-eye"></i> @lang('Move to Under Review')
            </button>
            <button type="button" class="btn btn-sm btn-outline--danger" id="btnBulkCancel">
                <i class="las la-times"></i> @lang('Cancel Selected')
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two custom-data-table">
                        <thead>
                            <tr>
                                <th style="width: 40px;">
                                    <input type="checkbox" id="selectAllCheckbox" class="form-check-input">
                                </th>
                                <th>@lang('User')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Payout Method & Details')</th>
                                <th>@lang('Cycle & Payable Date')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action (Pay / Disburse)')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payouts as $item)
                            @php
                                $st = strtolower($item->status);
                                $isPaid = in_array($st, ['confirmed', 'paid', 'approved']);
                            @endphp
                            <tr @if($isPaid) class="table-light" @endif>
                                <td>
                                    @if($isPaid)
                                        <span class="text-success" title="@lang('Payment Completed - Locked')">
                                            <i class="las la-lock fs-18"></i>
                                        </span>
                                    @else
                                        <input type="checkbox" class="form-check-input item-checkbox" value="{{ $item->id }}">
                                    @endif
                                </td>
                                <td>
                                    @if($item->user)
                                        <a href="{{ route('admin.users.detail', $item->user->id) }}" class="fw-bold text--base">
                                            {{ $item->user->fullname ?? $item->user->username }}
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $item->user->email }}</small>
                                    @else
                                        <span class="text-muted">@lang('User Deleted')</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold fs-15 text-dark">₹{{ showAmount($item->amount) }}</span>
                                    <br>
                                    <small class="text-muted">{{ showDateTime($item->created_at, 'd M, Y h:i A') }}</small>
                                </td>
                                <td>
                                    @if($item->payment_method == 'upi' && !empty($item->upi_id))
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge badge--dark">UPI</span>
                                            <code class="fw-semibold text-dark fs-12">{{ $item->upi_id }}</code>
                                        </div>
                                    @else
                                        <div class="lh-sm fs-12">
                                            <span class="badge badge--info mb-1">@lang('Bank Transfer')</span><br>
                                            <strong>{{ $item->bank_name ?? 'Bank' }}</strong> - A/C: <code>{{ $item->account_number }}</code><br>
                                            <small class="text-muted">IFSC: {{ $item->ifsc_code }} | A/C Holder: {{ $item->account_holder_name }}</small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="lh-sm">
                                        <span class="fw-semibold text-dark">{{ $item->month_cycle ?? 'Monthly Payout' }}</span><br>
                                        @if($item->eligible_at)
                                            @php
                                                $isEligible = now()->startOfDay()->gte(\Carbon\Carbon::parse($item->eligible_at)->startOfDay());
                                            @endphp
                                            @if($isEligible)
                                                <small class="text--success fw-semibold"><i class="las la-check-circle"></i> @lang('Payable Now (After') {{ showDateTime($item->eligible_at, 'd M') }})</small>
                                            @else
                                                <small class="text--warning fw-semibold"><i class="las la-clock"></i> @lang('Payable After') {{ showDateTime($item->eligible_at, 'd M, Y') }}</small>
                                            @endif
                                        @else
                                            <small class="text-muted">@lang('Standard Net-45')</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match($st) {
                                            'confirmed', 'paid', 'approved' => 'badge--success',
                                            'cancelled', 'declined'         => 'badge--danger',
                                            'under_review', 'unreviewed'    => 'badge--primary',
                                            default                         => 'badge--warning',
                                        };
                                        $badgeLabel = match($st) {
                                            'confirmed', 'paid', 'approved' => 'Confirmed',
                                            'cancelled', 'declined'         => 'Cancelled',
                                            'under_review', 'unreviewed'    => 'Under Review',
                                            default                         => 'Pending',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                    @if($isPaid && $item->transaction_id)
                                        <br><small class="text-muted fs-11">UTR: {{ $item->transaction_id }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($isPaid)
                                        <span class="badge bg-success bg-opacity-10 text-success py-2 px-3 fs-12 border border-success border-opacity-25 d-inline-flex align-items-center gap-1">
                                            <i class="las la-lock"></i> @lang('Paid & Locked')
                                        </span>
                                    @else
                                        <button class="btn btn-sm btn--success btnPaySingle"
                                            data-id="{{ $item->id }}"
                                            data-amount="{{ showAmount($item->amount) }}"
                                            data-username="{{ $item->user->username ?? 'User' }}"
                                            data-method="{{ strtoupper($item->payment_method) }}"
                                            data-acc="{{ $item->account_number ?? $item->upi_id }}"
                                            data-bank="{{ $item->bank_name ?? 'UPI' }}"
                                            data-ifsc="{{ $item->ifsc_code ?? '-' }}"
                                            data-holder="{{ $item->account_holder_name ?? '-' }}"
                                            data-txnid="{{ $item->transaction_id ?? '' }}"
                                            data-feedback="{{ $item->admin_feedback ?? '' }}"
                                            title="@lang('Confirm & Make Payment')">
                                            <i class="las la-wallet"></i> @lang('Pay Now')
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center py-4" colspan="100%">
                                    <i class="las la-inbox fs-36 text-muted mb-2 d-block"></i>
                                    @lang('No payout requests found.')
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($payouts->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($payouts) }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Pay & Confirm Payment Modal (Status automatically becomes Confirmed) --}}
<div id="paySingleModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg--success text-white">
                <h5 class="modal-title text-white"><i class="las la-money-bill-wave me-1"></i> @lang('Confirm & Make Payment')</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="paySingleForm">
                @csrf
                <div class="modal-body">
                    {{-- User & Amount Alert --}}
                    <div class="alert alert-info py-2 px-3 mb-3 fs-13 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted d-block">@lang('User'):</span>
                            <strong class="fs-14" id="modalUsername"></strong>
                        </div>
                        <div class="text-end">
                            <span class="text-muted d-block">@lang('Payment Amount'):</span>
                            <strong class="text--success fs-18">₹<span id="modalAmount"></span></strong>
                        </div>
                    </div>

                    {{-- Beneficiary Details Box --}}
                    <div class="card p-3 mb-3 bg-light border-0">
                        <h6 class="fs-12 text-uppercase text-muted fw-bold mb-2">
                            <i class="las la-university text-primary me-1"></i> @lang('Beneficiary Payment Destination'):
                        </h6>
                        <table class="table table-sm table-borderless mb-0 fs-12">
                            <tr>
                                <td class="text-muted py-1" style="width: 35%;">@lang('Method'):</td>
                                <td class="fw-bold py-1" id="modalMethod"></td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">@lang('A/C or UPI'):</td>
                                <td class="fw-bold text-primary py-1"><code id="modalAcc" class="fs-13"></code></td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">@lang('Bank / Holder'):</td>
                                <td class="fw-semibold py-1"><span id="modalBank"></span> (<span id="modalHolder"></span>)</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">@lang('IFSC'):</td>
                                <td class="fw-semibold py-1" id="modalIfsc"></td>
                            </tr>
                        </table>
                    </div>

                    {{-- Info Alert --}}
                    <div class="alert alert-warning py-2 px-3 mb-3 fs-12 border-0 bg-warning bg-opacity-10 text-dark">
                        <i class="las la-info-circle text-warning me-1"></i>
                        @lang('Submitting this reference will automatically mark this payout as') <strong>@lang('Confirmed (Paid)')</strong> @lang('and lock the transaction.')
                    </div>

                    {{-- Transaction ID / UTR --}}
                    <div class="form-group mb-3">
                        <label class="fw-bold">@lang('Transaction ID / UTR Reference') <span class="text-danger">*</span></label>
                        <input type="text" name="transaction_id" id="modalTxnId" class="form-control" required placeholder="@lang('e.g. UTR123456789 or IMPS987654')">
                        <small class="text-muted">@lang('Enter the bank UTR or UPI transaction ID for payment confirmation.')</small>
                    </div>

                    {{-- Feedback / Note --}}
                    <div class="form-group mb-2">
                        <label class="fw-bold">@lang('Admin Note / Receipt (Optional)')</label>
                        <input type="text" name="admin_feedback" id="modalFeedback" class="form-control" placeholder="@lang('e.g. Disbursed via Netbanking IMPS / Google Pay')">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success"><i class="las la-check-circle"></i> @lang('Confirm & Mark Paid')</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- Hidden Bulk Form --}}
<form id="bulkForm" action="{{ route('admin.payouts.bulk.action') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="action" id="bulkActionInput">
    <input type="hidden" name="feedback" id="bulkFeedbackInput">
    <div id="bulkIdsContainer"></div>
</form>

{{-- Bulk Cancel Reason Modal --}}
<div id="bulkCancelModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg--danger text-white">
                <h5 class="modal-title text-white"><i class="las la-exclamation-triangle me-1"></i> @lang('Cancel Selected Requests')</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>@lang('Are you sure you want to cancel all selected payout requests?')</p>
                <div class="form-group">
                    <label class="fw-bold">@lang('Cancellation Reason (Optional)')</label>
                    <textarea id="bulkCancelReason" class="form-control" rows="3" placeholder="@lang('e.g. Incorrect bank account details or fraud check.')"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                <button type="button" class="btn btn--danger" id="confirmBulkCancelBtn">@lang('Yes, Cancel Selected')</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')
<div class="d-flex flex-wrap gap-2 justify-content-end align-items-center">
    <form action="" method="GET" class="d-flex align-items-center gap-2">
        <select name="status" class="form-control" onchange="this.form.submit()">
            <option value="all">@lang('All Status')</option>
            <option value="pending" @selected(request()->status == 'pending')>@lang('Pending')</option>
            <option value="under_review" @selected(request()->status == 'under_review')>@lang('Under Review')</option>
            <option value="confirmed" @selected(request()->status == 'confirmed')>@lang('Confirmed')</option>
            <option value="cancelled" @selected(request()->status == 'cancelled')>@lang('Cancelled')</option>
        </select>
        <div class="input-group w-auto">
            <input type="text" name="search" class="form-control bg--white" placeholder="@lang('Search user, account, UPI...')" value="{{ request()->search }}">
            <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </form>
</div>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";

        // Bulk Selection Checkboxes
        function updateBulkUI() {
            var selectedCount = $('.item-checkbox:checked').length;
            if (selectedCount > 0) {
                $('#bulkActionBar').slideDown(200);
                $('#selectedCountBadge').text(selectedCount + ' @lang("Selected")');
            } else {
                $('#bulkActionBar').slideUp(200);
            }
        }

        $('#selectAllCheckbox').on('change', function () {
            $('.item-checkbox').prop('checked', $(this).prop('checked'));
            updateBulkUI();
        });

        $(document).on('change', '.item-checkbox', function () {
            updateBulkUI();
            if (!$(this).prop('checked')) {
                $('#selectAllCheckbox').prop('checked', false);
            }
        });

        // Bulk Move to Under Review
        $('#btnBulkReview').on('click', function () {
            var selected = $('.item-checkbox:checked');
            if (selected.length === 0) return;

            if (confirm('@lang("Move all ")' + selected.length + ' @lang("selected requests to Under Review?")')) {
                var form = $('#bulkForm');
                $('#bulkActionInput').val('under_review');
                $('#bulkFeedbackInput').val('');
                $('#bulkIdsContainer').empty();

                selected.each(function () {
                    $('#bulkIdsContainer').append('<input type="hidden" name="ids[]" value="' + $(this).val() + '">');
                });

                form.submit();
            }
        });

        // Bulk Cancel
        $('#btnBulkCancel').on('click', function () {
            var selected = $('.item-checkbox:checked');
            if (selected.length === 0) return;
            $('#bulkCancelModal').modal('show');
        });

        $('#confirmBulkCancelBtn').on('click', function () {
            var selected = $('.item-checkbox:checked');
            var reason = $('#bulkCancelReason').val();
            var form = $('#bulkForm');

            $('#bulkActionInput').val('cancelled');
            $('#bulkFeedbackInput').val(reason);
            $('#bulkIdsContainer').empty();

            selected.each(function () {
                $('#bulkIdsContainer').append('<input type="hidden" name="ids[]" value="' + $(this).val() + '">');
            });

            form.submit();
        });

        // Individual Pay Now Modal (Confirm Payment)
        $(document).on('click', '.btnPaySingle', function () {
            var id = $(this).data('id');
            var amount = $(this).data('amount');
            var username = $(this).data('username');
            var method = $(this).data('method');
            var acc = $(this).data('acc');
            var bank = $(this).data('bank');
            var ifsc = $(this).data('ifsc');
            var holder = $(this).data('holder');
            var txnid = $(this).data('txnid');
            var feedback = $(this).data('feedback');

            $('#modalUsername').text('@' + username);
            $('#modalAmount').text(amount);
            $('#modalMethod').text(method);
            $('#modalAcc').text(acc);
            $('#modalBank').text(bank);
            $('#modalIfsc').text(ifsc);
            $('#modalHolder').text(holder);

            $('#modalTxnId').val(txnid);
            $('#modalFeedback').val(feedback);

            $('#paySingleForm').attr('action', '{{ route("admin.payouts.pay.single", "") }}/' + id);
            $('#paySingleModal').modal('show');
        });


    })(jQuery);
</script>
@endpush
