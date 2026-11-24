@extends('admin.layouts.app')

@section('panel')
<div class="row">
    {{-- MAIN TABLE CARD --}}
    <div class="col-lg-12">
        <div class="card b-radius--10 shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom flex-wrap gap-2">
                <div>
                    <h5 class="card-title mb-0 text--dark">
                        <i class="las la-user-clock text--primary"></i> @lang('User Login Records')
                    </h5>
                    <div class="text-muted small mt-1">
                        @lang('Showing') <strong>{{ $loginLogs->firstItem() ?? 0 }}</strong> @lang('to') <strong>{{ $loginLogs->lastItem() ?? 0 }}</strong> @lang('of') <strong>{{ number_format($loginLogs->total()) }}</strong> @lang('total logins')
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn--success" data-bs-toggle="modal" data-bs-target="#exportModal" data-toggle="modal" data-target="#exportModal">
                        <i class="las la-file-download fs-15"></i> @lang('Export Data (With Filters)')
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table--light style--two text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>@lang('Sr. No.')</th>
                                <th>@lang('User ID')</th>
                                <th>@lang('Username')</th>
                                <th>@lang('Email')</th>
                                <th>@lang('Mobile')</th>
                                <th>@lang('Click ID')</th>
                                <th>@lang('Sub ID')</th>
                                <th>@lang('IP Address')</th>
                                <th>@lang('City')</th>
                                <th>@lang('Country')</th>
                                <th>@lang('Latitude')</th>
                                <th>@lang('Longitude')</th>
                                <th>@lang('Map')</th>
                                <th>@lang('Browser')</th>
                                <th>@lang('OS')</th>
                                <th>@lang('Login Time')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loginLogs as $log)
                            <tr>
                                {{-- 0. Sr. No. --}}
                                <td>
                                    <span class="text--dark fw-bold fs-12">{{ $loginLogs->firstItem() + $loop->index }}</span>
                                </td>

                                {{-- 1. User ID --}}
                                <td>
                                    <span class="badge badge--primary" style="font-size: 11px;">#{{ $log->user_id }}</span>
                                </td>

                                {{-- 2. Username --}}
                                <td>
                                    @if($log->user)
                                        <a href="{{ route('admin.users.detail', $log->user_id) }}" class="fw-bold text--primary fs-12">
                                            {{ $log->user->username }}
                                        </a>
                                    @else
                                        <span class="text-muted small">@lang('Deleted User')</span>
                                    @endif
                                </td>

                                {{-- 4. Email --}}
                                <td>
                                    @if(@$log->user->email)
                                        <span class="text--dark fw-semibold fs-12">{{ $log->user->email }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                {{-- 5. Mobile --}}
                                <td>
                                    @if(!empty(@$log->user->mobile))
                                        <span class="text--dark fw-semibold fs-12">{{ @$log->user->dial_code ? '+' . @$log->user->dial_code : '' }} {{ @$log->user->mobile }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                {{-- 6. Click ID --}}
                                <td>
                                    @if($log->click_id)
                                        <span class="badge badge--dark" style="font-size: 11px; font-family: monospace;" title="{{ $log->click_id }}">
                                            {{ $log->click_id }}
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                {{-- 7. Sub ID --}}
                                <td>
                                    @if($log->sub_id)
                                        <span class="badge badge--warning text-dark" style="font-size: 11px;" title="{{ $log->sub_id }}">
                                            {{ $log->sub_id }}
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                {{-- 8. IP Address --}}
                                <td>
                                    <span class="fw-bold">
                                        <a href="{{ route('admin.report.login.ipHistory', [$log->user_ip]) }}" class="text--primary fs-12">
                                            {{ $log->user_ip }}
                                        </a>
                                    </span>
                                </td>

                                {{-- 9. City --}}
                                <td>
                                    <span class="fs-12 text--dark">{{ $log->city ?: '—' }}</span>
                                </td>

                                {{-- 10. Country --}}
                                <td>
                                    <span class="fs-12 text--dark">{{ $log->country ?: '—' }}</span>
                                </td>

                                {{-- 11. Latitude --}}
                                <td>
                                    @if(!empty($log->latitude))
                                        <code class="text--dark fs-12">{{ $log->latitude }}</code>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                {{-- 12. Longitude --}}
                                <td>
                                    @if(!empty($log->longitude))
                                        <code class="text--dark fs-12">{{ $log->longitude }}</code>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                {{-- 13. Map Link --}}
                                <td>
                                    @if(!empty($log->latitude) && !empty($log->longitude))
                                        <a href="https://www.google.com/maps?q={{ $log->latitude }},{{ $log->longitude }}" target="_blank" class="btn btn-sm btn--primary" style="font-size: 11px; padding: 3px 8px;" title="@lang('View on Map')">
                                            <i class="las la-map-marker-alt"></i> @lang('Map')
                                        </a>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                {{-- 14. Browser --}}
                                <td>
                                    <span class="fs-12 fw-semibold text--dark">{{ __($log->browser ?: 'Unknown') }}</span>
                                </td>

                                {{-- 15. OS --}}
                                <td>
                                    <span class="text--dark fs-12">{{ __($log->os ?: 'Unknown') }}</span>
                                </td>

                                {{-- 16. Login Time --}}
                                <td>
                                    <div class="fs-12 fw-semibold text--dark">
                                        {{ showDateTime($log->created_at, 'd M Y, h:i A') }}
                                    </div>
                                    <div class="text-muted fs-11">
                                        {{ diffForHumans($log->created_at) }}
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center py-4" colspan="100%">
                                    <i class="las la-info-circle fs-18"></i> @lang('No login activity matches the selected filters.')
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($loginLogs->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($loginLogs) }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- EXPORT MODAL WITH COLUMN SELECTION ONLY --}}
<div class="modal fade" id="exportModal" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-white border-bottom py-3">
                <div>
                    <h5 class="modal-title text--dark fw-bold" id="exportModalLabel">
                        <i class="las la-file-export text--primary fs-20"></i> @lang('Export Login Activities (Select Columns)')
                    </h5>
                    <div class="text-muted small mt-1">
                        @lang('Select which columns you want to include in the exported CSV file.')
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.report.login.export') }}" method="GET" id="exportFilterForm">
                <input type="hidden" name="search" value="{{ request()->search }}">
                
                <div class="modal-body p-4">
                    {{-- 1. SPECIFIC USER SELECTION WITH LIVE SEARCH --}}
                    <div class="mb-4 bg-light p-3 rounded border">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                            <label class="form-label fw-bold text-dark fs-14 mb-0" for="exportUserIdSelect">
                                <i class="las la-user-circle text--primary fs-18"></i> @lang('Select User / Specific Person (Search by Name or ID)')
                            </label>
                            <span class="badge badge--primary">
                                <i class="las la-search"></i> @lang('Type Name to Search')
                            </span>
                        </div>
                        <select name="user_id" id="exportUserIdSelect" class="form-control select2-export-user" style="width: 100%;">
                            <option value="">@lang('-- All Users (Export Everyone) --')</option>
                            @if(isset($users) && $users->count())
                                @foreach($users as $u)
                                    @php
                                        $fullName = trim(($u->firstname ?? '') . ' ' . ($u->lastname ?? ''));
                                    @endphp
                                    <option value="{{ $u->id }}" {{ request()->user_id == $u->id ? 'selected' : '' }}>
                                        {{ $fullName ? $fullName . ' — ' : '' }}@ {{ $u->username }} (User #{{ $u->id }}){{ $u->email ? ' - ' . $u->email : '' }}{{ $u->mobile ? ' | ' . $u->mobile : '' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="text-muted fs-11 mt-2">
                            <i class="las la-info-circle text--info"></i> @lang('Search bar is active above: type user\'s first name, last name, username, ID, or email to filter. Selecting a user exports ONLY their login records; leaving it on "All Users" exports everyone.')
                        </div>
                    </div>

                    {{-- 2. CHOOSE COLUMNS TO EXPORT --}}
                    <div>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3 flex-wrap gap-2">
                            <h6 class="text--primary fw-bold mb-0">
                                <i class="las la-columns"></i> @lang('Choose Columns to Include in CSV')
                            </h6>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn--outline-primary py-1 px-3" id="selectAllColsBtn">
                                    <i class="las la-check-square"></i> @lang('Select All')
                                </button>
                                <button type="button" class="btn btn-sm btn--outline-secondary py-1 px-3" id="deselectAllColsBtn">
                                    <i class="las la-square"></i> @lang('Deselect All')
                                </button>
                            </div>
                        </div>

                        <div class="row g-2">
                            @php
                                $colList = [
                                    'sr_no'      => 'Sr. No.',
                                    'user_id'    => 'User ID',
                                    'username'   => 'Username',
                                    'full_name'  => 'Full Name',
                                    'email'      => 'Email',
                                    'mobile'     => 'Mobile Number',
                                    'click_id'   => 'Click ID',
                                    'sub_id'     => 'Sub ID',
                                    'ip'         => 'IP Address',
                                    'city'       => 'City',
                                    'country'    => 'Country',
                                    'latitude'   => 'Latitude',
                                    'longitude'  => 'Longitude',
                                    'browser'    => 'Browser',
                                    'os'         => 'Operating System (OS)',
                                    'login_time' => 'Login Date & Time',
                                ];
                            @endphp

                            @foreach($colList as $cKey => $cLabel)
                            <div class="col-md-6 col-sm-6">
                                <div class="form-check p-2 rounded bg-light border">
                                    <input class="form-check-input export-col-check ms-0 me-2" type="checkbox" name="columns[]" value="{{ $cKey }}" id="col_{{ $cKey }}" checked>
                                    <label class="form-check-label text-dark fw-semibold fs-13 cursor-pointer" for="col_{{ $cKey }}">
                                        {{ $cLabel }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light py-3 border-top d-flex justify-content-between">
                    <button type="button" class="btn btn--secondary" data-bs-dismiss="modal" data-dismiss="modal">
                        <i class="las la-times"></i> @lang('Close')
                    </button>
                    <button type="submit" class="btn btn--success px-4">
                        <i class="las la-file-download fs-16"></i> @lang('Download CSV / Export Now')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<div class="d-flex flex-wrap gap-2 align-items-center">
    @if(request()->routeIs('admin.report.login.history'))
    <form action="{{ route('admin.report.login.history') }}" method="GET" class="form-inline">
        <div class="input-group">
            <input type="text" name="search" class="form-control bg--white" placeholder="@lang('Quick Search...')"
                value="{{ request()->search }}">
            <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </form>

    <button type="button" class="btn btn--success" data-bs-toggle="modal" data-bs-target="#exportModal" data-toggle="modal" data-target="#exportModal">
        <i class="las la-file-download fs-16"></i> @lang('Export Data')
    </button>
    @endif

    @if(request()->routeIs('admin.report.login.ipHistory'))
    <a href="https://www.ip2location.com/{{ $ip }}" target="_blank" class="btn btn--primary">
        <i class="las la-search-location"></i> @lang('Lookup IP') {{ $ip }}
    </a>
    @endif
</div>
@endpush

@push('style')
<style>
    /* Styling for Select2 User Search in Export Modal */
    .select2-container {
        width: 100% !important;
    }
    .select2-container--open {
        z-index: 1065 !important;
    }
    .select2-dropdown {
        z-index: 1065 !important;
        border: 1.5px solid #4634ff !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18) !important;
        border-radius: 6px !important;
        background-color: #ffffff !important;
    }
    .select2-container--default .select2-selection--single {
        height: 44px !important;
        border: 1px solid #ced4da !important;
        border-radius: 6px !important;
        display: flex !important;
        align-items: center !important;
        background-color: #ffffff !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px !important;
        color: #212529 !important;
        font-size: 13.5px !important;
        padding-left: 14px !important;
        font-weight: 500 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px !important;
        right: 12px !important;
    }
    .select2-search--dropdown {
        padding: 10px 12px !important;
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #dee2e6 !important;
    }
    .select2-search--dropdown .select2-search__field {
        height: 38px !important;
        padding: 6px 12px !important;
        border: 1.5px solid #4634ff !important;
        border-radius: 5px !important;
        font-size: 13.5px !important;
        outline: none !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 0.15rem rgba(70, 52, 255, 0.15) !important;
    }
    .select2-results__option {
        padding: 9px 14px !important;
        font-size: 13px !important;
        border-bottom: 1px solid #f1f3f5 !important;
        color: #212529 !important;
    }
    .select2-results__option:last-child {
        border-bottom: none !important;
    }
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #4634ff !important;
        color: #ffffff !important;
    }
    .select2-container--default .select2-results__option--selected {
        background-color: #eef0ff !important;
        color: #4634ff !important;
        font-weight: 600 !important;
    }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";

        // Select All / Deselect All Column Checkboxes
        $('#selectAllColsBtn').on('click', function () {
            $('.export-col-check').prop('checked', true);
        });
        $('#deselectAllColsBtn').on('click', function () {
            $('.export-col-check').prop('checked', false);
        });

        // Initialize Select2 with live Search Bar inside Modal
        function initExportSelect2() {
            var $select = $('#exportUserIdSelect');
            if ($select.length) {
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
                $select.select2({
                    dropdownParent: $('#exportModal'),
                    placeholder: "@lang('-- All Users (Search by Name, Username or ID) --')",
                    allowClear: true,
                    width: '100%'
                });
            }
        }

        // Initialize on DOM ready
        initExportSelect2();

        // Re-initialize and focus search field when modal opens
        $('#exportModal').on('shown.bs.modal', function () {
            initExportSelect2();
            // Automatically focus the select2 dropdown or search input when modal is shown
            setTimeout(function () {
                $('#exportUserIdSelect').select2('open');
            }, 100);
        });
    })(jQuery);
</script>
@endpush
