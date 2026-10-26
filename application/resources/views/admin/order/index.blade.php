@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two custom-data-table">
                        <thead>
                            <tr>
                                <th>@lang('Order ID')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Product / Deal')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Source')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $item)
                            <tr>
                                <td><span class="fw-bold">#{{ $item->id }}</span></td>
                                <td>
                                    @if($item->user)
                                        <a href="{{ route('admin.users.detail', $item->user->id) }}">
                                            {{ $item->user->fullname ?? $item->user->username }}
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $item->user->email }}</small>
                                    @else
                                        <span class="text-muted">@lang('Guest / Deleted')</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->image_url)
                                            <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" style="width: 40px; height: 40px; object-fit: contain; border-radius: 6px; border: 1px solid #eee;">
                                        @endif
                                        <span title="{{ $item->product_name }}">{{ Str::limit($item->product_name, 35) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ showAmount($item->amount) }} {{ $item->currency ?? 'INR' }}</strong>
                                    @if($item->payout_amount > 0)
                                        <br><small class="text--success"><i class="las la-coins"></i> +{{ showAmount($item->payout_amount) }} @lang('Payout')</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge--dark text-capitalize">{{ str_replace('_', ' ', $item->source ?? 'referral') }}</span>
                                </td>
                                <td>{{ showDateTime($item->created_at, 'd M, Y h:i A') }}</td>
                                <td>
                                    @php
                                        $badge = match(strtolower($item->status)) {
                                            'confirmed', 'delivered' => 'badge--success',
                                            'cancelled', 'failed'    => 'badge--danger',
                                            'unreviewed'             => 'badge--primary',
                                            default                  => 'badge--warning',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($item->status) }}</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline--primary editBtn"
                                        data-id="{{ $item->id }}"
                                        data-status="{{ $item->status }}"
                                        data-amount="{{ $item->amount }}"
                                        data-payout="{{ $item->payout_amount }}"
                                        data-product="{{ $item->product_name }}"
                                        title="@lang('Update Status & Price')">
                                        <i class="la la-edit"></i> @lang('Update')
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">@lang('No orders tracked yet')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($orders->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($orders) }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Update Modal --}}
<div id="updateModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Update Order Status & Price')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="" method="POST" id="updateForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>@lang('Product Name')</label>
                        <input type="text" name="product_name" id="modalProductName" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label>@lang('Order Status')</label>
                        <select name="status" id="modalStatus" class="form-control" required>
                            <option value="pending">@lang('Pending')</option>
                            <option value="confirmed">@lang('Confirmed')</option>
                            <option value="unreviewed">@lang('Unreviewed')</option>
                            <option value="cancelled">@lang('Cancelled')</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>@lang('Actual Purchase Amount (INR)')</label>
                        <input type="number" step="0.01" name="amount" id="modalAmount" class="form-control" placeholder="0.00">
                    </div>
                    <div class="form-group mb-3">
                        <label>@lang('User Cashback / Payout (INR)')</label>
                        <input type="number" step="0.01" name="payout_amount" id="modalPayoutAmount" class="form-control" placeholder="0.00">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn--primary">@lang('Update Order')</button>
                </div>
            </form>
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
            <option value="confirmed" @selected(request()->status == 'confirmed')>@lang('Confirmed')</option>
            <option value="unreviewed" @selected(request()->status == 'unreviewed')>@lang('Unreviewed')</option>
            <option value="cancelled" @selected(request()->status == 'cancelled')>@lang('Cancelled')</option>
        </select>
        <div class="input-group w-auto">
            <input type="text" name="search" class="form-control bg--white" placeholder="@lang('Search by product, user...')" value="{{ request()->search }}">
            <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </form>
</div>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        $('.editBtn').on('click', function () {
            var modal = $('#updateModal');
            var id = $(this).data('id');
            var status = $(this).data('status');
            var amount = $(this).data('amount');
            var product = $(this).data('product');

            $('#modalStatus').val(status);
            $('#modalAmount').val(amount);
            $('#modalPayoutAmount').val($(this).data('payout') || 0);
            $('#modalProductName').val(product);
            $('#updateForm').attr('action', '{{ route("admin.orders.update", "") }}/' + id);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush
