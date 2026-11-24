@extends('admin.layouts.app')
@section('panel')
@include('admin.components.tabs.user')
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
                                <th>@lang('Email')</th>
                                <th>@lang('Mobile')</th>
                                <th>@lang('Country')</th>
                                <th>@lang('Joined At')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <span class="badge badge--primary" style="font-size: 11px;">#{{ $user->id }}</span>
                                </td>

                                <td>
                                    <a href="{{ route('admin.users.detail', $user->id) }}" class="fw-bold text--primary">
                                        {{ $user->username }}
                                    </a>
                                </td>

                                <td>
                                    {{ $user->email }}
                                </td>

                                <td>
                                    {{ $user->dial_code ? '+' . $user->dial_code : '' }} {{ $user->mobile ?: '—' }}
                                </td>

                                <td>
                                    <span class="fw-bold">{{ @$user->address->country ?: '—' }}</span>
                                </td>

                                <td>
                                    {{ showDateTime($user->created_at) }} <br> <small class="text-muted">{{ diffForHumans($user->created_at) }}</small>
                                </td>

                                <td>
                                    <a title="@lang('User Profile')" href="{{ route('admin.users.detail', $user->id) }}"
                                        class="btn btn-sm btn--primary">
                                        <i class="las la-eye text--shadow"></i>
                                    </a>
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
            @if ($users->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($users) }}
            </div>
            @endif
        </div>
    </div>


</div>
@endsection



@push('breadcrumb-plugins')
<div class="d-flex flex-wrap justify-content-end">
    <form action="" method="GET" class="form-inline">
        <div class="input-group justify-content-end">
            <input type="text" name="search" class="form-control bg--white" placeholder="@lang('Search by Username')"
                value="{{ request()->search }}">
            <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </form>
</div>
@endpush