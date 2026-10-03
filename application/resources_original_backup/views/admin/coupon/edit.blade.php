@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body px-4">
                    <form method="post" action="{{route('admin.coupon.update', $coupon->id)}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Title') </label>
                                    <input type="text" class="form-control" name="title" placeholder="@lang('Title')"
                                         value="{{$coupon->title}}" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Category') </label>
                                    <select class="form-control" name="category_id" required>
                                        @foreach($categories as $item)
                                            <option value="{{$item->id}}" {{$item->id== $coupon->category_id ? "selected" : ''}}> {{__($item->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Store')</label>
                                    <select class="form-control" name="store_id" required>
                                        @foreach($stores as $item)
                                            <option value="{{$item->id}}" {{$item->id== $coupon->store_id ? "selected" : ''}}> {{__($item->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Code') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Code')"
                                        name="code" value="{{$coupon->code}}" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Link') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Link')"
                                        name="link" value="{{$coupon->link}}" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Sale') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Sale')"
                                        name="sale" value="{{$coupon->sale}}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Discount Offer') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Discount Offer')"
                                        name="discount_offer" value="{{$coupon->discount_offer}}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Cashback Amount(%)') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Cashback Amount')"
                                        name="cashback_amount" value="{{$coupon->cashback_amount}}" />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="type" class="font-weight-bold">@lang('Status')</label>
                                    <select name="status" class="form-select" required>
                                        <option value="1" {{$coupon->status ==1 ?"selected": ""}}>@lang('Active')</option>
                                        <option value="0" {{$coupon->status ==0 ?"selected": ""}}>@lang('Inactive')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Start Date') </label>
                                    <input type="date" class="form-control" placeholder="@lang('Start Date')"
                                        name="start_date" value="{{ \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d') }}" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('End Date') </label>
                                    <input type="date" class="form-control" placeholder="@lang('End Date')"
                                        name="expire_date" value="{{ \Carbon\Carbon::parse($coupon->expire_date)->format('Y-m-d') }}" required />
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Coupon Image') </label>
                                    <input type="file" class="form-control" name="image">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div>
                                    <label class="fw-bold">@lang('Uploaded coupon image') </label>
                                    <img src="{{ getImage(getFilePath('coupon') . '/' . $coupon->image) }}" class="img-fluid" style="height: 110px;width:100%;" alt="@lang('Coupon')">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Description') </label>
                                    <textarea name="description" class="form-control trumEdit" id="description">@php echo $coupon->description; @endphp</textarea>
                                </div>
                            </div>                            
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_featured">@lang('Featured')</label>
                                    <label class="switch m-0" for="is_featured">
                                        <input type="checkbox" class="toggle-switch" name="is_featured" id="is_featured" {{ $coupon->is_featured ?
                                            'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_exclusive">@lang('Exclusive')</label>
                                    <label class="switch m-0" for="is_exclusive">
                                        <input type="checkbox" class="toggle-switch" name="is_exclusive"  id="is_exclusive" {{ $coupon->is_exclusive ?
                                            'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_popular">@lang('Popular')</label>
                                    <label class="switch m-0" for="is_popular">
                                        <input type="checkbox" class="toggle-switch" name="is_popular" id="is_popular" {{ $coupon->is_popular ?
                                            'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_sale">@lang('Sale')</label>
                                    <label class="switch m-0" for="is_sale">
                                        <input type="checkbox" class="toggle-switch" name="is_sale" id="is_sale" {{ $coupon->is_sale ?
                                            'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_cashback">@lang('Cashback')</label>
                                    <label class="switch m-0" for="is_cashback">
                                        <input type="checkbox" class="toggle-switch" name="is_cashback" id="is_cashback" {{ $coupon->is_cashback ?
                                            'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_earning">@lang('Earning')</label>
                                    <label class="switch m-0" for="is_earning">
                                        <input type="checkbox" class="toggle-switch" name="is_earning" id="is_earning" {{ $coupon->is_earning ?
                                            'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
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
