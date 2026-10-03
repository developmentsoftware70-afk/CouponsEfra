<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(){
        $pageTitle = 'Stores';
        $stores = Store::latest()->paginate(getPaginate(10));
        return view('admin.store.index',compact('pageTitle','stores'));
    }
    public function store(Request $request) {
        $request->validate(
          [
              'name' =>'required',
              'show_on_front' =>'required',
              'image' => ['required','image',new FileTypeValidate(['jpg','jpeg','png','gif'])]
            
          ]);
  
          $store = new Store();
          $store->name=$request->name;
          $store->description=$request->description;
          $store->status=1 ;
          $store->show_on_front=$request->show_on_front;
  
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

    public function update(Request $request) {
        $request->validate(
          [
              'name' =>'required',
              'show_on_front' =>'required',
              'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png','gif'])]
            
          ]);
  
          $store =  Store::findOrFail($request->id);
          $store->name=$request->name;
          $store->description=$request->description;
          $store->status=$request->status ? 1 : 0;
          $store->show_on_front=$request->show_on_front ? 1 : 0;
  
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
          return back()->withNotify($notify);
        
      }
      public function delete(Request $request) {
        $store =  Store::findOrFail($request->id);
        $filePath  =  getFilePath('store') . '/' . $store->image;
        fileManager()->removeFile($filePath);
        $store->delete();
        $notify[] = ['success','Store has been deleted successfully'];
        return back()->withNotify($notify);
    }

    public function changeStatus(Request $request)  {  
        $service =  Store::findOrFail($request->id);
        $service->status = $request->status;
        $service->save();
        $notify[] = ['success', 'Product reject successfully.'];
        return back()->withNotify($notify);
    }

}
