<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Store;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class StoreController extends Controller
{  
    public function index(){
        $pageTitle = 'Stores';
        $user = auth()->user();
        $stores = Store::where('user_id',$user->id)->latest()->paginate(getPaginate()); 
        return view($this->activeTemplate.'user.store.index',compact('pageTitle','stores'));
    }

    public function active(){
        $pageTitle = 'Pending Stores';
        $user = auth()->user();
        $stores = Store::where('status', 1)->where('user_id',$user->id)->orderBy('created_at','desc')->paginate(getPaginate()); 
        return view($this->activeTemplate.'user.store.index',compact('pageTitle','stores'));
    }
    public function pending(){
        $pageTitle = 'Pending Stores';
        $user = auth()->user();
        $stores = Store::where('status', 0)->where('user_id',$user->id)->orderBy('created_at','desc')->paginate(getPaginate()); 
        return view($this->activeTemplate.'user.store.index',compact('pageTitle','stores'));
    }

    public function create() {
        $pageTitle = 'Create Store';
        $user = auth()->user();
        $stores = Store::where('status', 1)->where('user_id',$user->id)->get(); 
        return view($this->activeTemplate.'user.store.create', compact('pageTitle','stores'));

    } 
    public function store(Request $request) {
        $user = auth()->user();
        if( $user->coupon_count <=0){
            $notify[] = ['warning','Please subscribe to new plan. You coupon credit is over'];
            return back()->withNotify($notify);
        }
        $request->validate(
          [
              'name' =>'required',
              'image' => ['required','image',new FileTypeValidate(['jpg','jpeg','png','gif'])]
            
          ]);
  
          $store = new Store();
          $store->name=$request->name;
          $store->user_id = auth()->user()->id;
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
          $notify[] = ['success','Store has been created successfully'];
          return back()->withNotify($notify);
        
    }
    public function edit($id) {
        $pageTitle = "Store Edit";
        $stores = Store::findOrFail($id); 
        return view($this->activeTemplate.'user.store.edit', compact('pageTitle','stores'));
    }
    public function update(Request $request, $id) {
        $request->validate(
          [
              'name' =>'required',
              'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png','gif'])]
            
          ]);
  
          $store =  Store::findOrFail($request->id);
          $store->name=$request->name;
          $store->description=$request->description;
          $store->status=$request->status ? 1 : 0;
  
          if ($request->hasFile('image')) {
              try {
                  $old = $store->image;
                  $store->image = fileUploader($request->image, getFilePath('store'), getFileSize('store'), $old);
              } catch (\Exception $exp) {
                  $notify[] = ['error', 'Couldn\'t upload your image'];
                  return back()->withNotify($notify);
              }
          }
  
          $store->save();
          $notify[] = ['success','Store has been Updated successfully'];
        //   return back()->withNotify($notify);
        return to_route('user.store.index')->withNotify($notify);
        
      }

      public function changeStatus(Request $request)  {  
        $service =  Store::findOrFail($request->id);
        $service->status = $request->status;
        $service->save();
        $notify[] = ['success', 'Product reject successfully.'];
        return back()->withNotify($notify);
    }

}
