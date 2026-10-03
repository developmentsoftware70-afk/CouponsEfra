@extends('admin.layouts.app')
@section('panel')

<div class="row mb-none-30">
    <div class="col-lg-12 mb-30">
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.plan.update',$plan->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="name" class="font-weight-bold">@lang('Name')</label>
                                <input type="text" name="name" id="name"
                                    class="form-control " value="{{$plan->name}}"
                                    required>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="price" class="font-weight-bold">@lang('Price')</label>
                                <input step="any" type="number" name="price" id="price"
                                    class="form-control" value="{{showAmount($plan->price)}}"
                                     required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="coupon_count" class="font-weight-bold">@lang('Coupon Count')</label>
                                <input step="any" type="number" name="coupon_count" id="coupon_count"
                                    class="form-control" value="{{$plan->coupon_count}}"
                                     required>
                            </div>
                        </div>
                            
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="type" class="font-weight-bold">@lang('Status')</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{$plan->status ==1 ?"selected": ""}}>@lang('Active')</option>
                                    <option value="0" {{$plan->status ==0 ?"selected": ""}}>@lang('Inactive')</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="font-weight-bold">@lang('Content')</label>
                            <div class="content-fields">
                                @if(@$plan->content)
                                    @foreach(json_decode(@$plan->content) as $key => $value)
                                        <div class="row mb-2 content-field align-items-center">
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <input type="text" name="contents[{{ $key }}]" id="content_{{ $key }}" value="{{ $value }}"
                                                        class="form-control" placeholder="@lang('Content')">
                                                </div>
                                            </div>
                                            <div class="col-2 align-self-center">
                                                <button type="button" class="btn btn btn--danger text--white removePlanContent mb-3"><i class="la la-times"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="col-12">
                                <div id="planContent"></div>
                            </div>

                            <div class="col-2">
                                <button type="button" class="btn btn--primary addPlanContent ms-0"><i class="fa fa-plus"></i> @lang('Add More Content')</button>
                            </div>
                        </div>
                        </div>

                        <div class="row text-end">
                            <div class="col-lg-12 ">
                                <div class="form-group float-end p-3">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg"> @lang('Update Plan')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')
<a href="{{route('admin.plan.index')}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i
        class="las la-angle-double-left"></i>@lang('Go Back')</a>
@endpush

@push('script')

<script>
    "use strict";
    (function ($) {

        var fileAdded = 0;
        $('.addPlanContent').on('click', function () {

            $("#planContent").append(`
                    <div class="row">
                        <div class="col-10">
                            <div class="form-group">
                                        <input type="text" name="contents[]" id="content" value="{{ old('contents.0') }}"
                                            class="form-control" placeholder="@lang('Content')">
                            </div>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn btn--danger text--white planContentDelete"><i class="la la-times ms-0"></i></button>
                        </div>
                    </div>
                `)
        });

        $(document).on('click', '.planContentDelete', function () {
            fileAdded--;
            $(this).closest('.row').remove();
        });



          // Remove content field
            $(document).on('click', '.removePlanContent', function() {
                $(this).closest('.content-field').remove();
            });

    })(jQuery);
</script>

@endpush

