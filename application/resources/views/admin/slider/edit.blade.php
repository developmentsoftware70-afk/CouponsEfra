@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body px-4">
                    <form method="post" action="{{route('admin.slider.update',$slider->id)}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Title') </label>
                                    <input type="text" class="form-control" name="title" placeholder="@lang('Title')" value="{{$slider->title}}" required>
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
                                    <input type="text" class="form-control" name="link" value="{{$slider->link}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-bold">@lang('status') <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required> 
                                    <option value="0" {{ @$slider->status == 0 ? 'selected' : '' }}>@lang('Inactive')</option>
                                    <option value="1" {{ @$slider->status == 1 ? 'selected' : '' }}>@lang('Active')</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Description') </label>
                                    <textarea class="form-control"  name="description" rows="5">{{$slider->description}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <label class="fw-bold">@lang('Uploaded slider image') </label>
                                    <img src="{{ getImage(getFilePath('slider') . '/' . $slider->image) }}" class="img-fluid" style="height: 110px;width:100%;" alt="@lang('slider')">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col text-end">
                                <button type="submit" class="btn btn--primary btn-global">@lang('Update')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
