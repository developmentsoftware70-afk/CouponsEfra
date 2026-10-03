@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body px-4">
                    <form method="post" action="{{route('admin.coupon.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Title') </label>
                                    <input type="text" class="form-control" name="title" placeholder="@lang('Title')"
                                        name="email_from" value="" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Category') </label>
                                    <select class="form-control" name="category_id" required>
                                        @foreach($categories as $item)
                                            <option value="{{$item->id}}"> {{__($item->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Store')</label>
                                    <select class="form-control" name="store_id" required>
                                        @foreach($stores as $item)
                                            <option value="{{$item->id}}"> {{__($item->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Code') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Code')"
                                        name="code" value="" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Link') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Link')"
                                        name="link" value="" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Sale') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Sale')"
                                        name="sale" value="" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Discount Offer') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Discount Offer')"
                                        name="discount_offer" value="" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Cashback Amount(%)') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Cashback Amount')"
                                        name="cashback_amount" value="" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Start Date') </label>
                                    <input type="date" class="form-control" placeholder="@lang('Start Date')"
                                        name="start_date" value="" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('End Date') </label>
                                    <input type="date" class="form-control" placeholder="@lang('End Date')"
                                        name="expire_date" value="" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Coupon Image') </label>
                                    <input type="file" class="form-control" name="image">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type" class="font-weight-bold">@lang('Status')</label>
                                    <select name="status" class="form-select" required>
                                        <option value="1">@lang('Active')</option>
                                        <option value="0">@lang('Inactive')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Description') </label>
                                    <textarea name="description" class="form-control trumEdit" id="description"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_featured">@lang('Featured')</label>
                                    <label class="switch m-0" for="is_featured">
                                        <input type="checkbox" class="toggle-switch" name="is_featured" id="is_featured" >
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_exclusive">@lang('Exclusive')</label>
                                    <label class="switch m-0" for="is_exclusive">
                                        <input type="checkbox" class="toggle-switch" name="is_exclusive"  id="is_exclusive">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_popular">@lang('Popular')</label>
                                    <label class="switch m-0" for="is_popular">
                                        <input type="checkbox" class="toggle-switch" name="is_popular" id="is_popular">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_sale">@lang('Sale')</label>
                                    <label class="switch m-0" for="is_sale">
                                        <input type="checkbox" class="toggle-switch" name="is_sale" id="is_sale">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_cashback">@lang('Cashback')</label>
                                    <label class="switch m-0" for="is_cashback">
                                        <input type="checkbox" class="toggle-switch" name="is_cashback" id="is_cashback">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <label class="fw-bold" for="is_earning">@lang('Earning')</label>
                                    <label class="switch m-0" for="is_earning">
                                        <input type="checkbox" class="toggle-switch" name="is_earning" id="is_earning">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-end">
                                <button type="submit" class="btn btn--primary btn-global">@lang('Save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
