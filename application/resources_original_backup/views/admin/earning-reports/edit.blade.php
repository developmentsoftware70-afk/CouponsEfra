@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body px-4">
                    <form method="post" action="{{route('admin.earning-reports.update', $earningReport->id)}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Deal Type') </label>
                                    <select class="form-control" name="deal_type" required>
                                        @foreach($dealTypes as $type)
                                            <option value="{{ $type }}" {{ $earningReport->deal_type == $type ? 'selected' : '' }}>
                                                {{ __($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('User ID & Name') </label>
                                    <select class="form-control" name="user_id" required>
                                        @foreach($users as $item)
                                            <option value="{{$item->id}}" {{ $item->id == $earningReport->user_id ? 'selected' : '' }}>{{ ($item->firstname ?? '') . ' ' . ($item->lastname ?? '') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Compaign Name') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Compaign Name')"
                                        name="compaign_name" value="{{$earningReport->compaign_name}}" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Sale Amount') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Sale Amount')"
                                        name="sale_amount" value="{{$earningReport->sale_amount}}" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Earning Amount') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Earning Amount')"
                                        name="earning_amount" value="{{$earningReport->earning_amount}}" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Report Type')</label>
                                    <select class="form-control" name="report_type" required>
                                        <option value="@lang('Monthly')" {{$earningReport->report_type == 'Monthly' ?"selected": ""}}>@lang('Monthly')</option>
                                        <option value="@lang('Daily')" {{$earningReport->report_type == 'Daily' ?"selected": ""}}>@lang('Daily')</option>
                                    </select>
                                </div>
                            </div>
                          
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Upload Report') | <a href="{{ getImage(getFilePath('earningReport') . '/' . $earningReport->report_file) }}" class="btn btn-sm btn-info p-0 m-0" target="_blank">View Report</a> </label>
                                    <input type="file" class="form-control" name="report_file">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type" class="font-weight-bold">@lang('Payment Status')</label>
                                    <select name="payment_status" class="form-select" required>
                                        <option value="0" {{$earningReport->payment_status ==0 ?"selected": ""}}>@lang('Pending')</option>
                                        <option value="1" {{$earningReport->payment_status ==1 ?"selected": ""}}>@lang('Approved')</option>
                                        <option value="2" {{$earningReport->payment_status ==2 ?"selected": ""}}>@lang('Disbursed')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type" class="font-weight-bold">@lang('Status')</label>
                                    <select name="status" class="form-select" required>
                                        <option value="1" {{$earningReport->status ==1 ?"selected": ""}}>@lang('Active')</option>
                                        <option value="0" {{$earningReport->status ==0 ?"selected": ""}}>@lang('Inactive')</option>
                                    </select>
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
