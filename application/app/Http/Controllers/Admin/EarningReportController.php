<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EarningReport;
use App\Models\User;
use Illuminate\Http\Request;

class EarningReportController extends Controller
{
    public function index() {
        $pageTitle = 'Earning Report List';
        $earningReports = EarningReport::with(['user'])->latest()->paginate(getPaginate(10));
        return view('admin.earning-reports.index', compact('pageTitle', 'earningReports'));
    }
    public function create() {
        $pageTitle = 'Earning Report Create';
        $users = User::where('status', 1)->where('signup_type', [1, 2])->get();
        return view('admin.earning-reports.create', compact('pageTitle', 'users'));
    }
    public function store(Request $request) {
        $request->validate(
            [
                'deal_type' =>'required',
                'user_id' =>'required',
                'compaign_name' =>'required',
                'sale_amount' =>'required',
                'earning_amount' =>'required',
                'report_type' =>'required',
                'payment_status' =>'required',
                'status' =>'required',
            ]);
                
            $earningReport = new EarningReport();
            $earningReport->deal_type=$request->deal_type;
            $earningReport->user_id=$request->user_id;
            $earningReport->compaign_name=$request->compaign_name;
            $earningReport->sale_amount=$request->sale_amount;
            $earningReport->earning_amount=$request->earning_amount;
            $earningReport->report_type=$request->report_type;
            $earningReport->payment_status=$request->payment_status;
            $earningReport->status=$request->status;
            
            if ($request->hasFile('report_file')) {
                try {
                    $earningReport->report_file = fileUploader($request->report_file, getFilePath('earningReport'));
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your image'];
                    return back()->withNotify($notify);
                }
            }
            $earningReport->save();

            $notify[] = ['success','Earning report has been created successfully'];
            return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = "Update Earning Report";

        $earningReport = EarningReport::findOrFail($id);
        $users = User::where('status', 1)->where('signup_type', [1, 2])->get(); 

        // Deal types – you can fetch from DB or config if needed
        $dealTypes = ['Coupon', 'Cashback', 'Deals'];

        return view('admin.earning-reports.edit', compact('pageTitle', 'earningReport', 'users', 'dealTypes'));
    }

    public function update(Request $request, $id) {
        $request->validate(
            [
                'deal_type' =>'required',
                'user_id' =>'required',
                'compaign_name' =>'required',
                'sale_amount' =>'required',
                'earning_amount' =>'required',
                'report_type' =>'required',
                'payment_status' =>'required',
                'status' =>'required',
            ]);


            $earningReport = EarningReport::findOrFail($id);

            $earningReport->deal_type=$request->deal_type;
            $earningReport->user_id=$request->user_id;
            $earningReport->compaign_name=$request->compaign_name;
            $earningReport->sale_amount=$request->sale_amount;
            $earningReport->earning_amount=$request->earning_amount;
            $earningReport->report_type=$request->report_type;
            $earningReport->payment_status=$request->payment_status;
            $earningReport->status=$request->status;

            if ($request->hasFile('report_file')) {
                try {
                    $old = $earningReport->report_file;
                    $earningReport->report_file = fileUploader($request->report_file, getFilePath('earningReport'),'',$old);
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your image'];
                    return back()->withNotify($notify);
                }
            }
            $earningReport->save();

            $notify[] = ['success','Earning report has been updated successfully'];
            // return back()->withNotify($notify);
            return to_route('admin.earning-reports.index')->withNotify($notify);
    }

    public function delete(Request $request) {
        $earningReport = EarningReport::findOrFail($request->id);
        $earningReport->delete();

        $notify[] = ['success','Earning report has been deleted successfully'];
        return back()->withNotify($notify);

    }
}
