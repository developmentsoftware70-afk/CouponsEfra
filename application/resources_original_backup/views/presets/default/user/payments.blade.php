@extends($activeTemplate.'layouts.master')

@section('content')
<div class="wishlist-area">
    <div class="account-form">
        <div class="row">
            <div class="col-lg-12">
                @php
                    $groupedReports = $paymentReports->groupBy('deal_type')->map->values();
                @endphp

                @foreach ($groupedReports as $dealType => $reports)
                    <h5 class="mt-5 fw-bold">{{ __($dealType) }}</h5>

                    <div class="table-responsive mb-3 border p-3 d-flex justify-content-between align-items-center">
                        <table class="table mb-0" style="min-width: 80%;">
                            <thead class="bg-light">
                                <tr>
                                    <th>@lang('Campaign Name')</th>
                                    <th>@lang('Sale Amount')</th>
                                    <th>@lang('Pending')</th>
                                    <th>@lang('Approved')</th>
                                    <th>@lang('Disbursal')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="@lang('Campaign Name')">
                                        {{ __($reports->first()->compaign_name ?? 'N/A') }}
                                    </td>
                                    <td data-label="@lang('Sale Amount')">
                                        {{ showAmount($reports->sum('sale_amount')) }} {{ $general->cur_text }}
                                    </td>
                                    <td data-label="@lang('Pending')">
                                        {{ showAmount($reports->where('payment_status', 0)->sum('earning_amount')) }} {{ $general->cur_text }}
                                    </td>
                                    <td data-label="@lang('Approved')">
                                        {{ showAmount($reports->where('payment_status', 1)->sum('earning_amount')) }} {{ $general->cur_text }}
                                    </td>
                                    <td data-label="@lang('Disbursal')">
                                        {{ showAmount($reports->where('payment_status', 2)->sum('earning_amount')) }} {{ $general->cur_text }}
                                    </td>
                                    <td data-label="@lang('Actions')">
                                        <div class="button--group gap-1 text-center">
                                            @if ($reports->first()->report_file)
                                                <a href="{{ getImage(getFilePath('earningReport') . '/' . $reports->first()->report_file) }}"
                                                class="btn btn-sm btn-dark" target="_blank">
                                                    @lang('Download')
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-dark mb-2" disabled>@lang('Download')</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endforeach
                @if ($paymentReports->hasPages())
                    <div class="d-flex justify-content-end py-4">
                        {{ paginateLinks($paymentReports) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
