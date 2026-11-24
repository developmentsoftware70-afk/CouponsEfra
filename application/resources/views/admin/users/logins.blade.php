@extends('admin.layouts.app')

@section('panel')
<div class="row">

    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table--light style--two text-nowrap">
                        <thead>
                            <tr>
                                <th>@lang('User ID')</th>
                                <th>@lang('Username')</th>
                                <th>@lang('IP')</th>
                                <th>@lang('Browser')</th>
                                <th>@lang('OS')</th>
                                <th>@lang('Login Time')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loginLogs as $log)
                            <tr>
                                <td>
                                    <span class="badge badge--primary" style="font-size: 11px;">#{{ $log->user_id }}</span>
                                </td>

                                <td>
                                    <a href="{{ route('admin.users.detail', $log->user_id) }}" class="text--primary fw-bold">
                                        {{ @$log->user->username }}
                                    </a>
                                </td>

                                <td>
                                    <span class="fw-bold">
                                        <a href="{{ route('admin.report.login.ipHistory', [$log->user_ip]) }}">{{ $log->user_ip }}</a>
                                    </span>
                                </td>

                                <td>
                                    {{ __($log->browser ?: 'Unknown') }}
                                </td>

                                <td>
                                    {{ __($log->os ?: 'Unknown') }}
                                </td>

                                <td>
                                    {{ showDateTime($log->created_at) }} <br> <small class="text-muted">{{ diffForHumans($log->created_at) }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if($loginLogs->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($loginLogs) }}
            </div>
            @endif
        </div><!-- card end -->
    </div>


</div>
@endsection