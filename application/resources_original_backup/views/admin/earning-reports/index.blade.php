@extends('admin.layouts.app')
@section('panel')

<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two custom-data-table">
                        <thead>
                            <tr>
                                <th>@lang('Deal Type')</th>
                                <th>@lang('User Id')</th>
                                <th>@lang('Compaign Name')</th>
                                <th>@lang('Sale Amount')</th>
                                <th>@lang('Earning Amount')</th>
                                <th>@lang('Report Type')</th>
                                <th><i class="las la-download"></i></th>
                                <th>@lang('Status')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($earningReports as $item)
                            <tr>
                                <td>{{__($item->deal_type)}}</td>
                                <td>{{__($item->user_id)}}</td>
                                <td>{{__($item->compaign_name)}}</td>
                                <td>{{ showAmount($item->sale_amount) }} {{ $general->cur_text }}</td>
                                <td>{{ showAmount($item->earning_amount) }} {{ $general->cur_text }}</td>
                                <td>{{__($item->report_type)}}</td>
                                <td><a href="{{ getImage(getFilePath('earningReport') . '/' . $item->report_file) }}" class="" target="_blank"><i class="las la-download"></i></a></td>
                                
                                <td>
                                    @if($item->status == 1)
                                    <span class="badge badge--success">@lang('Active')</span>
                                    @else 
                                    <span class="badge badge--danger">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{route("admin.earning-reports.edit",$item->id)}}" title="@lang('Edit')"
                                            class="btn btn-sm btn--success">
                                            <i class="la la-edit"></i>
                                        </a>
                                        <button title="@lang('Remove')"
                                            class="btn btn-sm btn--danger ms-1 deleteBtn" data-id="{{$item->id}}">
                                            <i class="la la-trash"></i>
                                        </button>
                                     
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table><!-- table end -->
                    @if ($earningReports->hasPages())
                        <div class="card-footer py-4">
                            {{ paginateLinks($earningReports) }}
                        </div>
                    @endif
                </div>
            </div>
        </div><!-- card end -->
    </div>
</div>

{{-- DELETE  MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editModalLabel">@lang('Delete Confirmation')</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="las la-times"></i></button>
            </div>
            <form class="form-horizontal" method="post" action="{{route('admin.earning-reports.delete')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                   <span>@lang('Are You Sure Delete This Earning Report')</span>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary btn-global" id="btn-save"
                        value="add">@lang('Delete')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('breadcrumb-plugins')
<a href="{{route('admin.earning-reports.create')}}" class="btn btn-sm btn--primary"><i
        class="las la-plus"></i>@lang('Add New')</a>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";

        // Ensure DOM is ready
        $(document).ready(function () {
            // Delete
            $('.deleteBtn').on('click', function () {
                var modal = $('#deleteModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
        });

    })(jQuery);
</script>
@endpush
