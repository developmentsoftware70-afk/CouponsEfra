<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DealCategory;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class DealCategoryController extends Controller
{
    public function index(){
        $pageTitle = 'Deal Category';
        $dealCategories = DealCategory::latest()->paginate(getPaginate(10));
        return view('admin.deal-category.index',compact('pageTitle','dealCategories'));
    }

    public function store(Request $request) {
      $request->validate(
        [
            'title' =>'required',
            'description' =>'',
            'link' =>'',
            'is_show' =>'',
            'status' =>'',
            'image' => ['required','image',new FileTypeValidate(['jpg','jpeg','png','gif'])]
          
        ]);

        $dealCategory = new DealCategory();
        $dealCategory->title=$request->title;
        $dealCategory->description=$request->description;
        $dealCategory->link=$request->link;
        $dealCategory->is_show=$request->is_show;
        $dealCategory->status=$request->status;

        if ($request->hasFile('image')) {
            try {
                $dealCategory->image = fileUploader($request->image, getFilePath('dealCategory'), getFileSize('dealCategory'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $dealCategory->save();
        $notify[] = ['success','Deal category has been created successfully'];
        return back()->withNotify($notify);
      
    }
    public function update(Request $request) {
      $request->validate(
        [
            'title' =>'required',
            'description' =>'',
            'link' =>'',
            'is_show' =>'',
            'status' =>'',
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png','gif'])]
          
        ]);

        $dealCategory =  DealCategory::findOrFail($request->id);
        $dealCategory->title=$request->title;
        $dealCategory->description=$request->description;
        $dealCategory->link=$request->link;
        $dealCategory->is_show=$request->is_show;
        $dealCategory->status=$request->status;

        if ($request->hasFile('image')) {
            try {
                $old = $dealCategory->image;
                $dealCategory->image = fileUploader($request->image, getFilePath('dealCategory'), getFileSize('dealCategory'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $dealCategory->save();
        $notify[] = ['success','Deal category has been Updated successfully'];
        return back()->withNotify($notify);
      
    }
    public function delete(Request $request) {
        $dealCategory =  DealCategory::findOrFail($request->id);
        $filePath  =  getFilePath('dealCategory') . '/' . $dealCategory->image;
        fileManager()->removeFile($filePath);
        $dealCategory->delete();
        $notify[] = ['success','Deal category has been deleted successfully'];
        return back()->withNotify($notify);
    }
}
