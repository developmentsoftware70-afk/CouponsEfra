@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body px-4">
                    <form method="post" action="{{route('admin.deal.update', $deal->id)}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Title') </label>
                                    <input type="text" class="form-control" name="title" placeholder="@lang('Title')"
                                         value="{{ $deal->title }}" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Deal Category') </label>
                                    <select class="form-control" name="deal_category_id" required>
                                        @foreach($dealCategories as $item)
                                            <option value="{{ $item->id }}" {{ $item->id== $deal->deal_category_id ? "selected" : '' }}> {{__($item->title)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Link') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Link')"
                                        name="link" value="{{ $deal->link }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Button Label') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Button Label')"
                                        name="button_label" value="{{ $deal->button_label }}">
                                </div>
                            </div>
                            
                           
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_affiliate">@lang('Affiliate')</label>
                                    <label class="switch m-0" for="is_affiliate">
                                        <input type="checkbox" class="toggle-switch" name="is_affiliate" id="is_affiliate" {{ $deal->is_affiliate ?
                                            'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_earning">@lang('Earning')</label>
                                    <label class="switch m-0" for="is_earning">
                                        <input type="checkbox" class="toggle-switch" name="is_earning" id="is_earning" {{ $deal->is_earning ?
                                            'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_show">@lang('Show On Home')</label>
                                    <label class="switch m-0" for="is_show">
                                        <input type="checkbox" class="toggle-switch" name="is_show"  id="is_show" {{ $deal->is_show ?
                                            'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="status">@lang('Status')</label>
                                    <label class="switch m-0" for="status">
                                        <input type="checkbox" class="toggle-switch" name="status" id="status" {{ $deal->status ?
                                            'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Description') </label>
                                    <textarea name="description" class="form-control trumEdit" id="description">@php echo $deal->description; @endphp</textarea>
                                </div>
                            </div>  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Deal Image') </label>
                                    <input type="file" class="form-control" name="image">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <label class="fw-bold">@lang('Uploaded deal image') </label>
                                    <img src="{{ getImage(getFilePath('deal') . '/' . $deal->image) }}" class="img-fluid" style="height: 110px;width:100%;" alt="">
                                </div>
                            </div>   
                            
                        </div>
                        <div class="row">
                            <div class="col text-start">
                                <button type="submit" class="btn btn--primary btn-global">@lang('Update')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
