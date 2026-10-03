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
                                <th>@lang('Title')</th>
                                <th>@lang('Description')</th>
                                <th>@lang('Link')</th>
                                <th>@lang('Image')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sliders as $item)
                            <tr>
                                <td>{{__($item->title)}}</td>
                                <td>{{__($item->description)}}</td>
                                <td><a href="{{$item->link}}">{{__($item->link)}}</a></td>
                                <td><img src="{{ getImage(getFilePath('slider') . '/' . $item->image) }}" alt="@lang('slider')" style="width: 50px"></td>
                                <td>
                                    @if($item->status == 1)
                                    <span class="badge badge--success">@lang('Active')</span>
                                    @elseif($item->status == 0)
                                    <span class="badge badge--danger">@lang('Inactive')</span>
                                    @else
                                    <span class="badge">@lang('N/A')</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{route('admin.slider.edit',$item->id)}}" title="@lang('Edit')"
                                            class="btn btn-sm btn--success">
                                            <i class="la la-edit"></i>
                                        </a>
                                        <button title="@lang('Delete')"
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
            <form class="form-horizontal" method="post" action="{{route('admin.slider.delete')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                   <span>@lang('Are You Sure Delete This Slider')</span>

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
<a href="{{route('admin.slider.create')}}" class="btn btn-sm btn--primary" ><i class="las la-plus"></i>@lang('Add New')</a>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        $('.deleteBtn').on('click', function () {
            var modal = $('#deleteModal');
            modal.find('input[name=id]').val($(this).data('id'));

            modal.modal('show');

        });

        
    })(jQuery);
</script>
@endpush