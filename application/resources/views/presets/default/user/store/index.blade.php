@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="account-form">
    <div class="table-responsive--sm table-responsive">
        <div class="mb-3 d-flex justify-content-end">
            <div class="input--group trans-search d-flex">
                <input type="text" class="form--control" name="search_table" placeholder="Search...">
                <button class="input-group-text bg--base search-btn text-white">
                    <i class="las la-search"></i>
                </button>
            </div>
        </div>
        <table class="table table--light style--two custom-data-table custom-table">
            <thead>
                <tr>
                    <th>@lang('Name')</th>
                    <th>@lang('Image')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stores as $item)
                <tr>
                    <td>{{__($item->name)}}</td>
                    <td><img src="{{ getImage(getFilePath('store') . '/' . $item->image) }}" alt="@lang('store-image')" style="width: 50px"></td>
                    <td>
                        @if($item->status == 1)
                        
                        <span class="badge badge--success">@lang('Active')</span>
                        @else 
                        
                        <span class="badge badge--danger">@lang('Inactive')</span>
                        @endif
                    </td>
                    <td>                                    
                        <div class="button--group">
                            <button title="@lang('Change Status')"
                            data-id="{{$item->id}}"
                            class="btn btn--base btn--sm outline changeStatusBtn">
                            <i class="las la-check"></i>
                            </button>

                            <a href="{{route("user.store.edit",$item->id)}}" class="btn btn--base btn--sm ">
                                <i class="la la-edit"></i>
                            </a>
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
        @if ($stores->hasPages())
            <div class="d-flex justify-content-end py-4">
                {{ paginateLinks($stores) }}
            </div>
        @endif
      
    </div>
</div>

{{-- status change modal --}}
<div id="statusModal" class= modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mb-2">@lang('Status Change Confirmation')</h5>
                <button type="button" class="btn btn--danger btn--sm" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{route('user.store.status.change')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="store-status modal-body">
                    <p>@lang('Are you sure to') <span class="fw-bold">@lang('see')</span> <span
                            class="fw-bold withdraw-amount text-success"></span> @lang('status change') <span
                            class="fw-bold withdraw-user"></span>?
                    </p>
                    <div class="form-grp">
                        <label for="status" class="form--label font-weight-bold">@lang('Status')</label>
                        <select name="status" class="form-control form--control form-select" id="status">
                            <option value="1">@lang('Approved')</option>
                            <option value="0">@lang('Pending')</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submitButton" class="btn btn--base ">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";
            
            // change status
            $('.changeStatusBtn').on('click', function () {
                var modal = $('#statusModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            
        })(jQuery);
        
        // search 
        (function ($) {
                "use strict";

                $('.custom-table').css('padding-top', '0px');
                var tr_elements = $('.custom-table tbody tr');
                
                $(document).on('input', 'input[name=search_table]', function () {
                    "use strict";
                    var search = $(this).val().toUpperCase();
                    var match = tr_elements.filter(function (idx, elem) {
                        return $(elem).text().trim().toUpperCase().indexOf(search) >= 0 ? elem : null;
                    }).sort();
                    var table_content = $('.custom-table tbody');
                    if (match.length == 0) {
                        table_content.html('<tr><td colspan="100%" class="text-center">Data Not Found</td></tr>');
                    } else {
                        table_content.html(match);
                    }
                });
        })(jQuery);
    </script>
@endpush