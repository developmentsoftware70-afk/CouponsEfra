@extends($activeTemplate.'layouts.master')
@section('content')


<div class="account-form">
    <div class="col-md-12">
        <div class="card responsive-filter-card mb-4">
            <div class="card-body">
                <form action="">
                    <div class="d-flex flex-wrap align-items-center gap-4">
                        <div class="flex-grow-1">
                            <label class="form--label">@lang('Transaction Number')</label>
                            <input type="text" placeholder="@lang('Transaction Number')" name="search" value="{{ request()->search }}"
                                class="form--control">
                        </div>
                        <div class="flex-grow-1">
                            <label class="form--label">@lang('Type')</label>
                            <select name="type" class="form--control">
                                <option value="">@lang('All')</option>
                                <option value="+" @selected(request()->type == '+')>@lang('Plus')</option>
                                <option value="-" @selected(request()->type == '-')>@lang('Minus')</option>
                            </select>
                        </div>
                        <div class="flex-grow-1">
                            <label class="form--label">@lang('Remark')</label>
                            <select class="form--control" name="remark">
                                <option value="">@lang('Any')</option>
                                @foreach($remarks as $remark)
                                <option value="{{ $remark->remark }}" @selected(request()->remark ==
                                    $remark->remark)>{{ __(keyToTitle($remark->remark)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-grow-1 align-self-end">
                            <button class="btn btn--base w-100 mb-3"><i class="las la-filter"></i>
                                @lang('Filter')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card custom--card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table--responsive--lg">
                        <thead>
                            <tr>
                                <th>@lang('Trx')</th>
                                <th>@lang('Transacted')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Post Balance')</th>
                                <th>@lang('Detail')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                            <tr>
                                <td data-label="Trx">
                                    <strong>{{ $trx->trx }}</strong>
                                </td>

                                <td data-label="Transacted">
                                    {{ showDateTime($trx->created_at) }}<br>{{ diffForHumans($trx->created_at)
                                    }}
                                </td>

                                <td class="budget" data-label="Amount">
                                    <span
                                        class="fw-bold @if($trx->trx_type == '+')text-success @else text-danger @endif">
                                        {{ $trx->trx_type }} {{showAmount($trx->amount)}} {{ $general->cur_text
                                        }}
                                    </span>
                                </td>

                                <td class="budget" data-label="Post Balance">
                                    {{ showAmount($trx->post_balance) }} {{ __($general->cur_text) }}
                                </td>


                                <td data-label="Detail">{{ __($trx->details) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%" data-label="No Data">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if($transactions->hasPages())
        <div class="d-flex justify-content-end mt-3 mb-1">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>


@endsection