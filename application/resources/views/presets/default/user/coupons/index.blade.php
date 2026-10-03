@extends($activeTemplate . 'layouts.master')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="account-form">
            <div class="mb-3 d-flex justify-content-end">
                <div class="input--group trans-search d-flex">
                    <input type="text" class="form--control" name="search_table" placeholder="Search...">
                    <button class="input-group-text bg--base search-btn text-white">
                        <i class="las la-search"></i>
                    </button>
                </div>
            </div>
            <div class="table-responsive--sm table-responsive">
                <table class="table table--responsive--lg custom-table">
                    <thead>
                        <tr>
                            <th>@lang('Title')</th>
                            <th>@lang('Image')</th>
                            <th>@lang('Category')</th>
                            <th>@lang('Code')</th>
                            <th>@lang('Featured')</th>
                            <th>@lang('Exclusive')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons as $item)
                        <tr>
                            <td data-label="@lang('Title')">{{__($item->title)}}</td>
                            <td data-label="@lang('Image')">
                                <img src="{{ getImage(getFilePath('store') . '/' . @$item->store->image) }}" alt="@lang('store-image')" style="width: 80px">
                            </td>
                            <td data-label="@lang('Category')">{{__(@$item->category->name)}}</td>
                            <td data-label="@lang('Code')">{{__($item->code)}}</td>
                            <td data-label="@lang('Featured')">
                                @if($item->is_featured == 1)
                                
                                <span class="base-color"><i class="fas fa-check-circle"></i></span>
                                @else 
                                
                                <span><i class="fas fa-times"></i></span>
                                @endif
                            </td>
                            <td data-label="@lang('Exclusive')">
                                @if($item->is_exclusive == 1)
                                
                                <span><i class="fas fa-check-circle"></i></span>
                                @else 
                                
                                <span><i class="fas fa-times"></i></span>
                                @endif
                            </td>
                            <td data-label="@lang('Status')">
                                @if($item->status == 1)
                                
                                <span class="badge badge--success">@lang('Active')</span>
                                @else 
                                
                                <span class="badge badge--danger">@lang('Pending')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Actions')">
                                <div class="button--group">
                                    <a href="{{route("user.coupons.edit",$item->id)}}" title="@lang('Edit')"
                                        class="btn btn--base btn--sm">
                                        <i class="la la-edit"></i>
                                    </a>                                     
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td data-label="{{ __($emptyMessage) }} class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table><!-- table end -->
                @if ($coupons->hasPages())
                    <div class="d-flex justify-content-end py-4">
                        {{ paginateLinks($coupons) }}
                    </div>
                @endif
            </div>
        </div><!-- card end -->
    </div>
</div>
@endsection

@push('script')
<script>
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