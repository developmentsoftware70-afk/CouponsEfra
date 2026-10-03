<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Rules\FileTypeValidate;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Store;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index() {
        $pageTitle = 'Coupons List';
        $user = auth()->user();
        $coupons = Coupon::with(['category', 'store'])->where('user_id',$user->id)->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.coupons.index', compact('pageTitle', 'coupons'));
    }
    public function active() {
        $pageTitle = 'Active Coupons';
        $user = auth()->user();
        $coupons = Coupon::with(['category', 'store'])->where('status', 1)->where('user_id',$user->id)->orderBy('created_at','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'user.coupons.index', compact('pageTitle', 'coupons'));
    }
    public function pending() {
        $pageTitle = 'Pending Coupons';
        $user = auth()->user();
        $coupons = Coupon::with(['category', 'store'])->where('status', 0)->where('user_id',$user->id)->orderBy('created_at','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'user.coupons.index', compact('pageTitle', 'coupons'));
    }
    public function create() {
        $pageTitle = 'Coupon Create';
        $user = auth()->user();
        $categories = Category::where('status', 1)->get(); 
        $stores = Store::where('status', 1)->where('user_id',$user->id)->get(); 
        return view($this->activeTemplate.'user.coupons.create', compact('pageTitle','categories', 'stores'));
    }   
    public function store(Request $request) {
        $user = auth()->user();
        if( $user->coupon_count <=0){
            $notify[] = ['warning','Please subscribe to new plan. You coupon credit is over'];
            return back()->withNotify($notify);
        }
        $request->validate(
            [
                'title' =>'required',
                'category_id' =>'required',
                'store_id' =>'required',
                'code' =>'required',
                'start_date' =>'required',
                'expire_date' =>'required',
                'link' =>'required',
            ]);
                
            $coupon = new Coupon();
            $coupon->title=$request->title;
            $coupon->category_id=$request->category_id;
            $coupon->store_id=$request->store_id;
            $coupon->user_id = auth()->user()->id;
            $coupon->code=$request->code;
            $coupon->start_date=$request->start_date;
            $coupon->expire_date=$request->expire_date;
            $coupon->link=$request->link;
            $coupon->is_featured=$request->is_featured ? 1 : 0;
            $coupon->is_exclusive=$request->is_exclusive ? 1 : 0;
            $coupon->status=0;
            $coupon->description = $request->description;
            $coupon->save();
            $user->coupon_count -=1;
            $user->save();

            $notify[] = ['success','Coupon has been created successfully'];
            return back()->withNotify($notify);
    }
    public function edit($id) {
        $pageTitle = "Update";
        $coupon = Coupon::findOrFail($id);
        $categories = Category::where('status', 1)->get(); 
        $stores = Store::where('status', 1)->get(); 
        return view($this->activeTemplate.'user.coupons.edit', compact('pageTitle','coupon','categories','stores'));
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
            ]);


            $coupon = Coupon::findOrFail($id);

            $coupon->title=$request->title;
            $coupon->category_id=$request->category_id;
            $coupon->store_id=$request->store_id;
            $coupon->code=$request->code;
            $coupon->start_date=$request->start_date;
            $coupon->expire_date=$request->expire_date;
            $coupon->link=$request->link;
            $coupon->is_featured=$request->is_featured ? 1 : 0;
            $coupon->is_exclusive=$request->is_exclusive ? 1 : 0;
            $coupon->status=1;
            $coupon->description=$request->description;
            $coupon->save();

            $notify[] = ['success','Coupon has been updated successfully'];
            return back()->withNotify($notify);
    }
    public function storeCreate(Request $request) {
        $request->validate(
          [
              'name' =>'required',
              'image' => ['required','image',new FileTypeValidate(['jpg','jpeg','png','gif'])]
            
          ]);
  
          $store = new Store();
          $store->name=$request->name;
          $store->description=$request->description;
          $store->status=1 ;
  
          if ($request->hasFile('image')) {
              try {
                  $store->image = fileUploader($request->image, getFilePath('store'), getFileSize('store'));
              } catch (\Exception $exp) {
                  $notify[] = ['error', 'Couldn\'t upload your image'];
                  return back()->withNotify($notify);
              }
          }
  
          $store->save();        
        return view($this->activeTemplate.'user.coupons.create', compact('pageTitle','categories', 'stores'));
        
    }


}
