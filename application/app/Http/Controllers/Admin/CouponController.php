<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Store;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index() {
        $pageTitle = 'Coupons List';
        $coupons = Coupon::with(['category', 'store'])->latest()->paginate(getPaginate(10));
        return view('admin.coupon.index', compact('pageTitle', 'coupons'));
    }
    public function create() {
        $pageTitle = 'Coupon Create';
        $categories = Category::where('status', 1)->get(); 
        $stores = Store::where('status', 1)->get(); 
        return view('admin.coupon.create', compact('pageTitle','categories', 'stores'));
    }
    public function store(Request $request) {
        $request->validate(
            [
                'title' =>'required',
                'category_id' =>'required',
                'store_id' =>'required',
                'code' =>'required',
                'start_date' =>'required',
                'expire_date' =>'required',
                'link' =>'required',
                'sale' =>'',
                'discount_offer' =>'',
                'cashback_amount' =>'',
            ]);
                
            $coupon = new Coupon();
            $coupon->title=$request->title;
            $coupon->category_id=$request->category_id;
            $coupon->store_id=$request->store_id;
            $coupon->code=$request->code;
            $coupon->start_date=$request->start_date;
            $coupon->expire_date=$request->expire_date;
            $coupon->link=$request->link;
            $coupon->sale=$request->sale;
            $coupon->discount_offer=$request->discount_offer;
            $coupon->cashback_amount=$request->cashback_amount;
            $coupon->is_featured=$request->is_featured ? 1 : 0;
            $coupon->is_exclusive=$request->is_exclusive ? 1 : 0;
            $coupon->is_popular=$request->is_popular ? 1 : 0;
            $coupon->is_sale=$request->is_sale ? 1 : 0;
            $coupon->is_cashback=$request->is_cashback ? 1 : 0;
            $coupon->is_earning=$request->is_earning ? 1 : 0;
            $coupon->status=$request->status;
            $coupon->description=$request->description;
            
            if ($request->hasFile('image')) {
                try {
                    $coupon->image = fileUploader($request->image, getFilePath('coupon'));
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your image'];
                    return back()->withNotify($notify);
                }
            }
            $coupon->save();

            $notify[] = ['success','Coupon has been created successfully'];
            return back()->withNotify($notify);
    }

    public function edit($id) {
        $pageTitle = "Update";
        $coupon = Coupon::findOrFail($id);
        $categories = Category::where('status', 1)->get(); 
        $stores = Store::where('status', 1)->get(); 
        return view('admin.coupon.edit', compact('pageTitle','coupon','categories','stores'));
    }
    public function update(Request $request, $id) {
        $request->validate(
            [
                'title' =>'required',
                'category_id' =>'required',
                'store_id' =>'required',
                'code' =>'required',
                'start_date' =>'required',
                'expire_date' =>'required',
                'link' =>'required',
                'sale' =>'',
                'discount_offer' =>'',
                'cashback_amount' =>'',
            ]);


            $coupon = Coupon::findOrFail($id);

            $coupon->title=$request->title;
            $coupon->category_id=$request->category_id;
            $coupon->store_id=$request->store_id;
            $coupon->code=$request->code;
            $coupon->start_date=$request->start_date;
            $coupon->expire_date=$request->expire_date;
            $coupon->link=$request->link;
            $coupon->sale=$request->sale;
            $coupon->discount_offer=$request->discount_offer;
            $coupon->cashback_amount=$request->cashback_amount;
            $coupon->is_featured=$request->is_featured ? 1 : 0;
            $coupon->is_exclusive=$request->is_exclusive ? 1 : 0;
            $coupon->is_popular=$request->is_popular ? 1 : 0;
            $coupon->is_sale=$request->is_sale ? 1 : 0;
            $coupon->is_cashback=$request->is_cashback ? 1 : 0;
            $coupon->is_earning=$request->is_earning ? 1 : 0;
            $coupon->status=$request->status ? 1: 0;
            $coupon->description=$request->description;

            if ($request->hasFile('image')) {
                try {
                    $old = $coupon->image;
                    $coupon->image = fileUploader($request->image, getFilePath('coupon'),'',$old);
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your image'];
                    return back()->withNotify($notify);
                }
            }
            $coupon->save();

            $notify[] = ['success','Coupon has been updated successfully'];
            // return back()->withNotify($notify);
            return to_route('admin.coupon.index')->withNotify($notify);
    }

    public function delete(Request $request) {
        $coupon = Coupon::findOrFail($request->id);
        $coupon->delete();

        $notify[] = ['success','Coupon has been deleted successfully'];
        return back()->withNotify($notify);

    }

    
    public function pending(){
        $pageTitle = "Pending Coupons";
        $coupons = Coupon::with(['category'])->where('status',0)->with('store')->orderBy('created_at','desc')->paginate(getPaginate());
        return view('admin.coupon.index',compact('pageTitle','coupons'));
    }

    public function approved(){
        $pageTitle = "Approved Coupons";
        $coupons = Coupon::with(['category'])->where('status',1)->with('store')->orderBy('created_at','desc')->paginate(getPaginate());
        return view('admin.coupon.index',compact('pageTitle','coupons'));
    }

    public function changeStatus(Request $request)  {  
        $coupon =  Coupon::findOrFail($request->id);
        $coupon->status = $request->status;
        $coupon->save();
        $notify[] = ['success', 'Product reject successfully.'];
        return back()->withNotify($notify);
    }

}
