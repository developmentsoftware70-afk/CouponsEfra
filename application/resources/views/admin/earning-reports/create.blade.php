@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body px-4">
                    <form method="post" action="{{route('admin.earning-reports.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Deal Type') </label>
                                    <select class="form-control" name="deal_type" required>
                                        <option value="@lang('Coupon')">@lang('Coupon')</option>
                                        <option value="@lang('Cashback')">@lang('Cashback')</option>
                                        <option value="@lang('Deals')">@lang('Deals')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('User ID') </label>
                                    <select class="form-control" name="user_id" required>
                                        @foreach($users as $item)
                                            <option value="{{$item->id}}">{{ ($item->firstname ?? '') . ' ' . ($item->lastname ?? '') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Compaign Name') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Compaign Name')"
                                        name="compaign_name" value="" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Sale Amount') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Sale Amount')"
                                        name="sale_amount" value="" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Earning Amount') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Earning Amount')"
                                        name="earning_amount" value="" required />
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Report Type')</label>
                                    <select class="form-control" name="report_type" required>
                                        <option value="@lang('Monthly')">@lang('Monthly')</option>
                                        <option value="@lang('Daily')">@lang('Daily')</option>
                                    </select>
                                </div>
                            </div>
                          
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Upload Report') </label>
                                    <input type="file" class="form-control" name="report_file">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type" class="font-weight-bold">@lang('Payment Status')</label>
                                    <select name="payment_status" class="form-select" required>
                                        <option value="0">@lang('Pending')</option>
                                        <option value="1">@lang('Approved')</option>
                                        <option value="2">@lang('Disbursed')</option>
                                    </select>
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
