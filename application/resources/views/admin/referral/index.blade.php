@extends('admin.layouts.app')
@section('panel')
<div class="row mb-4 gy-4">
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--primary text-white b-radius--10">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 small mb-1">@lang('Total Referrals')</h6>
                    <h3 class="text-white mb-0">{{ $totalReferrals }}</h3>
                </div>
                <i class="las la-users text-white-50" style="font-size: 42px;"></i>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--success text-white b-radius--10">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 small mb-1">@lang('Confirmed Rewards Paid')</h6>
                    <h3 class="text-white mb-0">{{ $general->cur_sym }}{{ showAmount($confirmedRewards) }}</h3>
                </div>
                <i class="las la-wallet text-white-50" style="font-size: 42px;"></i>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--warning text-white b-radius--10">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 small mb-1">@lang('Pending Rewards')</h6>
                    <h3 class="text-white mb-0">{{ $general->cur_sym }}{{ showAmount($pendingRewards) }}</h3>
                </div>
                <i class="las la-clock text-white-50" style="font-size: 42px;"></i>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--info text-white b-radius--10">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 small mb-1">@lang('Awaiting First Purchase')</h6>
                    <h3 class="text-white mb-0">{{ $awaitingPurchases }}</h3>
                </div>
                <i class="las la-shopping-cart text-white-50" style="font-size: 42px;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="show-filter mb-3 text-end">
            <button type="button" class="btn btn-outline--primary showFilterBtn btn-sm"><i class="las la-filter"></i> @lang('Filter')</button>
            <a href="{{ route('admin.referral.settings') }}" class="btn btn-outline--info btn-sm ms-2"><i class="las la-cog"></i> @lang('Referral Settings')</a>
        </div>
        <div class="card responsive-filter-card mb-4 {{ request()->has('status') || request()->has('search') ? '' : 'd-none' }}">
            <div class="card-body">
                <form action="">
                    <div class="d-flex flex-wrap gap-4">
                        <div class="flex-grow-1">
                            <label>@lang('Search User')</label>
                            <input type="text" name="search" value="{{ request()->search }}" class="form-control" placeholder="@lang('Username or email of referrer/friend')">
                        </div>
                        <div class="flex-grow-1">
                            <label>@lang('Status')</label>
                            <select name="status" class="form-control">
                                <option value="">@lang('All Status')</option>
                                <option value="pending_purchase" @selected(request()->status == 'pending_purchase')>@lang('Awaiting First Purchase')</option>
                                <option value="pending_confirmation" @selected(request()->status == 'pending_confirmation')>@lang('Pending Order Confirmation')</option>
                                <option value="confirmed" @selected(request()->status == 'confirmed')>@lang('Confirmed / Paid')</option>
                                <option value="rejected" @selected(request()->status == 'rejected')>@lang('Rejected / Cancelled')</option>
                            </select>
                        </div>
                        <div class="flex-grow-1 align-self-end">
                            <button class="btn btn--primary w-100 h-45"><i class="fas fa-search"></i> @lang('Filter')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two custom-data-table">
                        <thead>
                            <tr>
                                <th>@lang('Referrer (Invited By)')</th>
                                <th>@lang('Friend (Referee)')</th>
                                <th>@lang('Order Value')</th>
                                <th>@lang('Reward')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Joined Date')</th>
                                <th>@lang('Confirmed Date')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                            <tr>
                                <td>
                                    @if($log->referrer)
                                        <a href="{{ route('admin.users.detail', $log->referrer->id) }}" class="fw-bold">
                                            {{ $log->referrer->username }}
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $log->referrer->email }}</small>
                                    @else
                                        <span class="text-muted">@lang('Deleted User')</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->referee)
                                        <a href="{{ route('admin.users.detail', $log->referee->id) }}" class="fw-bold">
                                            {{ $log->referee->username }}
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $log->referee->email }}</small>
                                    @else
                                        <span class="text-muted">@lang('Deleted User')</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->order_amount > 0)
                                        <span class="fw-bold">{{ $general->cur_sym }}{{ showAmount($log->order_amount) }}</span>
                                        @if($log->order)
                                            <br><small class="text-muted">#{{ $log->order->order_number ?: $log->order->id }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted small">@lang('No purchase yet')</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text--success">{{ $general->cur_sym }}{{ showAmount($log->reward_amount) }}</span>
                                </td>
                                <td>
                                    @if($log->status == 'confirmed')
                                        <span class="badge badge--success">@lang('Confirmed')</span>
                                    @elseif($log->status == 'pending_confirmation')
                                        <span class="badge badge--warning">@lang('Pending Order Confirmation')</span>
                                    @elseif($log->status == 'rejected')
                                        <span class="badge badge--danger">@lang('Rejected')</span>
                                    @else
                                        <span class="badge badge--dark">@lang('Awaiting Purchase (< ₹' . (int)$general->referral_min_purchase . ')')</span>
                                    @endif
                                </td>
                                <td>
                                    {{ showDateTime($log->created_at, 'd M Y, h:i A') }}
                                </td>
                                <td>
                                    @if($log->confirmed_at)
                                        {{ showDateTime($log->confirmed_at, 'd M Y, h:i A') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage ?? 'No referral records found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($logs->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($logs) }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.referral.settings') }}" class="btn btn-sm btn--primary">
        <i class="la la-cog"></i> @lang('Referral Settings')
    </a>
@endpush
