@extends('admin.layouts.app')
@section('panel')
@include('admin.components.tabs.deal')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two custom-data-table">
                        <thead>
                            <tr>
                                <th>@lang('Title')</th>
                                <th>@lang('Image')</th>
                                <th>@lang('Deal Category')</th>
                                <th>@lang('Affiliate')</th>
                                <th>@lang('Earning')</th>
                                <th>@lang('Show On Home')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($deals as $item)
                            <tr>
                                <td>{{__($item->title)}}</td>
                                <td><img src="{{ getImage(getFilePath('deal') . '/' . @$item->image) }}" alt="{{__($item->title)}}" style="width: 80px"></td>
                                <td>{{__(@$item->deal_category->title)}}</td>
                                <td>
                                    @if($item->is_affiliate == 1)
                                    
                                    <span class="base-color"><i class="fas fa-check-circle"></i></span>
                                    @else 
                                    
                                    <span><i class="fas fa-times"></i></span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->is_earning == 1)
                                    
                                    <span class="base-color"><i class="fas fa-check-circle"></i></span>
                                    @else 
                                    
                                    <span><i class="fas fa-times"></i></span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->is_show == 1)
                                    
                                    <span class="badge badge--success">@lang('Yes')</span>
                                    @else 
                                    
                                    <span class="badge badge--danger">@lang('No')</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status == 1)
                                    
                                    <span class="badge badge--success">@lang('Active')</span>
                                    @else 
                                    
                                    <span class="badge badge--danger">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="button--group">
                                        @if($item->user_id == 0)
                                            <a href="{{route("admin.deal.edit",$item->id)}}" title="@lang('Edit')"
                                                class="btn btn-sm btn--success">
                                                <i class="la la-edit"></i>
                                            </a>
                                        @endif
                                        <button title="@lang('Change Status')"
                                         data-id="{{$item->id}}"
                                            class="btn btn-sm btn--primary changeStatusBtn">
                                            <i class="las la-check"></i>
                                        </button>
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
                    @if ($deals->hasPages())
                        <div class="card-footer py-4">
                            {{ paginateLinks($deals) }}
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
            <form class="form-horizontal" method="post" action="{{route('admin.deal.delete')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                   <span>@lang('Are You Sure Delete This Deal')</span>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary btn-global" id="btn-save"
                        value="add">@lang('Delete')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- status change modal --}}
<div id="statusModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mb-2">@lang('Status Change Confirmation')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{route('admin.deal.status.change')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="fw-bold">@lang('see')</span> <span
                            class="fw-bold withdraw-amount text-success"></span> @lang('status change') <span
                            class="fw-bold withdraw-user"></span>?
                    </p>
                    <div class="form-group">
                        <label for="status" class="font-weight-bold">@lang('Status')</label>
                        <select name="status" class="form-control" id="status">
                            <option value="1">@lang('Approved')</option>
                            <option value="0">@lang('Pending')</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submitButton" class="btn btn--primary btn-global">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div

@endsection


@push('breadcrumb-plugins')
<a href="{{route('admin.deal.create')}}" class="btn btn-sm btn--primary"><i
        class="las la-plus"></i>@lang('Add New')</a>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        // Delete
        $('.deleteBtn').on('click', function () {
            var modal = $('#deleteModal');
            modal.find('input[name=id]').val($(this).data('id'));

            modal.modal('show');

        });

          // change status
          $('.changeStatusBtn').on('click', function () {
            var modal = $('#statusModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.modal('show');
        });

        
    })(jQuery);
</script>
@endpush