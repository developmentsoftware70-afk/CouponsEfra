@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="row mb-none-30">
    <div class="col-lg-12 col-md-12 mb-30">
        <div class="account-form">
            <form method="post" action="{{route('user.coupons.update', $coupon->id)}}" enctype="multipart/form-data">
                @csrf
                <div class="row gy-4 align-items-center">
                    <div class="col-md-4">
                        <div class="form-grp">
                            <label class="label">@lang('Title') 
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form--control" name="title" placeholder="@lang('Title')"
                                    value="{{$coupon->title}}" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-grp">
                            <label class="label">@lang('Category')
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control form--control form-select" name="category_id" required>
                                @foreach($categories as $item)
                                    <option value="{{$item->id}}" {{$item->id== $coupon->category_id ? "selected" : ''}}> {{__($item->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-grp">
                            <label class="label">@lang('Store')
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control form--control form-select" name="store_id" required>
                                @foreach($stores as $item)
                                    <option value="{{$item->id}}" {{$item->id== $coupon->store_id ? "selected" : ''}}> {{__($item->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-grp">
                            <label class="label">@lang('Code') 
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form--control" placeholder="@lang('Code')"
                                name="code" value="{{$coupon->code}}" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-grp">
                            <label class="label">@lang('Link')
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form--control" placeholder="@lang('Link')"
                                name="link" value="{{$coupon->link}}" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-grp">
                            <label class="label">@lang('Start Date')
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control form--control" placeholder="@lang('Start Date')"
                                name="start_date" value="{{ \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d') }}" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-grp">
                            <label class="label">@lang('End Date')
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control form--control" placeholder="@lang('End Date')"
                                name="expire_date" value="{{ \Carbon\Carbon::parse($coupon->expire_date)->format('Y-m-d') }}" required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-grp">
                            <label class="label">@lang('Description') </label>
                            <textarea name="description" class="form-control form--control" id="description">@php echo $coupon->description; @endphp</textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form--check mt-4">
                            <label class="switch m-0 d-flex" for="is_featured">
                                <label class="form-check-label me-2" for="is_featured">@lang('Is Featured')</label>
                                <input type="checkbox" class="form-check-input toggle-switch" name="is_featured" id="is_featured" {{ $coupon->is_featured ?
                                    'checked' : null }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form--check mt-4">
                            <label class="switch m-0 d-flex" for="is_exclusive">
                                <label class="form-check-label me-2" for="is_exclusive">@lang('Is Exclusive')</label>
                                <input type="checkbox" class="form-check-input toggle-switch" name="is_exclusive"  id="is_exclusive" {{ $coupon->is_exclusive ?
                                    'checked' : null }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-end">
                        <button type="submit" class="btn btn--base btn-global">@lang('Update')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection