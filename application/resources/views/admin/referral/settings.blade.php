@extends('admin.layouts.app')
@section('panel')
<div class="row mb-none-30 justify-content-center">
    <div class="col-lg-8 col-md-10 mb-30">
        <div class="card b-radius--10">
            <div class="card-header bg--primary text-white p-3">
                <h5 class="text-white mb-0"><i class="las la-gift"></i> @lang('Refer & Earn Program Configuration')</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.referral.settings.update') }}" method="POST">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-12">
                            <div class="border rounded p-3 bg--white d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 fw-bold">@lang('Referral Program Status')</h6>
                                    <p class="text-muted small mb-0">@lang('Enable or disable the referral program platform-wide.')</p>
                                </div>
                                <label class="switch m-0">
                                    <input type="checkbox" class="toggle-switch" name="referral_status" {{ ($general->referral_status ?? 1) ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">@lang('Referral Reward Amount') ({{ $general->cur_text }})</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $general->cur_sym }}</span>
                                    <input type="number" step="0.01" min="0" name="referral_reward_amount" class="form-control" value="{{ $general->referral_reward_amount ?? 50.00 }}" required>
                                </div>
                                <small class="text-muted">@lang('Flat cash reward credited to referrer wallet per qualified friend.')</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">@lang('Minimum Purchase Requirement') ({{ $general->cur_text }})</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $general->cur_sym }}</span>
                                    <input type="number" step="0.01" min="0" name="referral_min_purchase" class="form-control" value="{{ $general->referral_min_purchase ?? 500.00 }}" required>
                                </div>
                                <small class="text-muted">@lang('Min order value friend must purchase to qualify for referral bonus.')</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-info bg--info text-white mb-0">
                                <h6 class="text-white mb-1"><i class="las la-info-circle"></i> @lang('Anti-Fraud & Safety Rules'):</h6>
                                <ul class="mb-0 ps-3 small text-white-50">
                                    <li class="text-white">@lang('One-time reward: Each referred user can only trigger ONE referral reward on their first qualifying order.')</li>
                                    <li class="text-white">@lang('Order Confirmation Hold: Reward remains in Pending state until the order is confirmed and return window closes, preventing return/cancellation losses.')</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn--primary h-45 px-4"><i class="fas fa-save me-1"></i> @lang('Save Settings')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.referral.index') }}" class="btn btn-sm btn--outline-primary">
        <i class="la la-list"></i> @lang('View Referral Logs')
    </a>
@endpush
