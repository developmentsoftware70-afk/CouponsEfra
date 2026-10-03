@extends($activeTemplate.'layouts.master')
@section('content')
<div class="wishlist-area">
    <div class="account-form">
        <div class="row">
            <div class="col-lg-12">
                <!-- Total Earnings Overview -->
                <div class="text-left mb-4">
                    <h6 class="dash-title">@lang('My Total Earning')</h6>
                    <p class="text-muted">
                        @lang('Your Total Earnings amount includes your Cashback + cashback + deals amount.')
                    </p>
                    <h6 class="text-muted mt-2">
                        <strong>{{ showAmount($totalEarnings) }} {{ $general->cur_text }}</strong>
                    </h6>
                </div>

                @php
                    $groupedReports = $earningReports->groupBy('deal_type')->map->values();
                @endphp

                @foreach ($groupedReports as $dealType => $reports)
                    <h5 class="mt-5 fw-bold">
                        {{ __($dealType) }}
                        <span class="float-end text-muted small">
                            @lang('Total'): <strong>{{ showAmount($reports->sum('earning_amount')) }} {{ $general->cur_text }}</strong>
                        </span>
                    </h5>

                    <div class="table-responsive mb-3 border p-3">
                        <table class="table">
                            <thead class="bg-light">
                                <tr>
                                    <th>@lang('SL')</th>
                                    <th>@lang('Campaign Name')</th>
                                    <th>@lang('Sale Amount')</th>
                                    <th>@lang('Earning')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports as $key =>$item)
                                    <tr>
                                        <td data-label="@lang('SL')">{{__($key + 1) }}</td>
                                        <td data-label="@lang('Campaign Name')">{{ __($item->compaign_name) }}</td>
                                        <td data-label="@lang('Sale Amount')">{{ showAmount($item->sale_amount) }} {{ $general->cur_text }}</td>
                                        <td data-label="@lang('Earning')">{{ showAmount($item->earning_amount) }} {{ $general->cur_text }}</td>
                                        <td data-label="@lang('Actions')">
                                            <div class="button--group gap-1 text-center">
                                                @if ($item->report_file)
                                                <a href="{{ getImage(getFilePath('earningReport') . '/' . $item->report_file) }}"
                                                    class="btn btn-sm btn-dark" target="_blank">
                                                    @lang('Download Report')
                                                </a>
                                                @endif
                                                {{-- 
                                                <a href="{{ route('user.earnings.view', $item->id) }}"
                                                   class="btn btn-sm btn--base">
                                                    @lang('View Report')
                                                </a>
                                                 --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-muted text-center">
                                            @lang('No data found under this category.')
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endforeach

                @if ($earningReports->hasPages())
                    <div class="d-flex justify-content-end py-4">
                        {{ paginateLinks($earningReports) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection