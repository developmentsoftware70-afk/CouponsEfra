<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DealCategory;
use App\Models\Deal;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index() {
        $pageTitle = 'Deal List';
        $deals = Deal::with(['deal_category'])->latest()->paginate(getPaginate(10));
        return view('admin.deal.index', compact('pageTitle', 'deals'));
    }
    public function create() {
        $pageTitle = 'Deal Create';
        $dealCategories = DealCategory::where('status', 1)->get(); 
        return view('admin.deal.create', compact('pageTitle','dealCategories'));
    }
    public function store(Request $request) {
        $request->validate(
            [
                'title' =>'required',
                'deal_category_id' =>'required',
                'description' =>'',
                'link' =>'',
                'button_label' =>'',
                'is_affiliate' =>'',
                'is_earning' =>'',
                'is_show' =>'',
                'status' =>'',
            ]);
                
            $deal = new Deal();
            $deal->title=$request->title;
            $deal->deal_category_id=$request->deal_category_id;
            $deal->button_label=$request->button_label;
            $deal->link=$request->link;
            $deal->is_affiliate=$request->is_affiliate ? 1 : 0;
            $deal->is_earning=$request->is_earning ? 1 : 0;
            $deal->is_show=$request->is_show ? 1 : 0;
            $deal->status=$request->status ? 1 : 0;
            $deal->description=$request->description;
            
            if ($request->hasFile('image')) {
                try {
                    $deal->image = fileUploader($request->image, getFilePath('deal'));
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your image'];
                    return back()->withNotify($notify);
                }
            }
            $deal->save();

            $notify[] = ['success','Deal has been created successfully'];
            return back()->withNotify($notify);
    }

    public function edit($id) {
        $pageTitle = "Update";
        $deal = Deal::findOrFail($id);
        $dealCategories = DealCategory::where('status', 1)->get();
        return view('admin.deal.edit', compact('pageTitle','deal','dealCategories'));
    }
    public function update(Request $request, $id) {
        $request->validate(
            [
                'title' =>'required',
                'deal_category_id' =>'required',
                'description' =>'',
                'link' =>'',
                'button_label' =>'',
                'is_affiliate' =>'',
                'is_earning' =>'',
                'is_show' =>'',
                'status' =>'',
            ]);


            $deal = Deal::findOrFail($id);

            $deal->title=$request->title;
            $deal->deal_category_id=$request->deal_category_id;
            $deal->button_label=$request->button_label;
            $deal->link=$request->link;
            $deal->is_affiliate=$request->is_affiliate ? 1 : 0;
            $deal->is_earning=$request->is_earning ? 1 : 0;
            $deal->is_show=$request->is_show ? 1 : 0;
            $deal->status=$request->status ? 1 : 0;
            $deal->description=$request->description;

            if ($request->hasFile('image')) {
                try {
                    $old = $deal->image;
                    $deal->image = fileUploader($request->image, getFilePath('deal'),'',$old);
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your image'];
                    return back()->withNotify($notify);
                }
            }
            $deal->save();

            $notify[] = ['success','Deal has been updated successfully'];
            // return back()->withNotify($notify);
            return to_route('admin.deal.index')->withNotify($notify);
    }

    public function delete(Request $request) {
        $deal = Deal::findOrFail($request->id);
        $deal->delete();

        $notify[] = ['success','Deal has been deleted successfully'];
        return back()->withNotify($notify);

    }

    
    public function pending(){
        $pageTitle = "Pending Deals";
        $deals = Deal::with(['deal_category'])->where('status',0)->orderBy('created_at','desc')->paginate(getPaginate());
        return view('admin.deal.index',compact('pageTitle','deals'));
    }

    public function approved(){
        $pageTitle = "Approved Deals";
        $deals = Deal::with(['deal_category'])->where('status',1)->orderBy('created_at','desc')->paginate(getPaginate());
        return view('admin.deal.index',compact('pageTitle','deals'));
    }

    public function changeStatus(Request $request)  {  
        $deal =  Deal::findOrFail($request->id);
        $deal->status = $request->status;
        $deal->save();
        $notify[] = ['success', 'Deal reject successfully.'];
        return back()->withNotify($notify);
    }

}

