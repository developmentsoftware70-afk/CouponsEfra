@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body px-4">
                    <form method="post" action="{{route('admin.slider.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Title') </label>
                                    <input type="text" class="form-control" name="title" placeholder="@lang('Title')" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Slider Image') </label>
                                    <input type="file" class="form-control" name="image">
                                </div>
                            </div>

                         
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Link') </label>
                                    <input type="text" class="form-control" name="link">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-bold">@lang('status') <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required> 
                                    <option value="0">@lang('Inactive')</option>
                                    <option value="1">@lang('Active')</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Description') </label>
                                    <textarea class="form-control"  name="description" > </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-end">
                                <button type="submit" class="btn btn--primary btn-global">@lang('Create')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
<a href="{{route('admin.slider.index')}}" class="btn btn-sm btn--primary" ><i class="las la-list"></i>@lang('All Slider')</a>
@endpush