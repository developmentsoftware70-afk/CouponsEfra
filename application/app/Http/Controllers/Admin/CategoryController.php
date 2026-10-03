<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $pageTitle = 'Category';
        $categories = Category::latest()->paginate(getPaginate(10));
        return view('admin.category.index',compact('pageTitle','categories'));
    }

    public function store(Request $request) {
      $request->validate(
        [
            'name' =>'required',
            'show_on_front' =>'required',
            'image' => ['required','image',new FileTypeValidate(['jpg','jpeg','png','gif'])]
          
        ]);

        $category = new Category();
        $category->name=$request->name;
        $category->description=$request->description;
        $category->status=1 ;
        $category->show_on_front=$request->show_on_front;

        if ($request->hasFile('image')) {
            try {
                $category->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $category->save();
        $notify[] = ['success','Category has been created successfully'];
        return back()->withNotify($notify);
      
    }
    public function update(Request $request) {
      $request->validate(
        [
            'name' =>'required',
            'show_on_front' =>'required',
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png','gif'])]
          
        ]);

        $category =  Category::findOrFail($request->id);
        $category->name=$request->name;
        $category->description=$request->description;
        $category->status=$request->status ? 1 : 0;
        $category->show_on_front=$request->show_on_front ? 1 : 0;

        if ($request->hasFile('image')) {
            try {
                $old = $category->image;
                $category->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $category->save();
        $notify[] = ['success','Category has been Updated successfully'];
        return back()->withNotify($notify);
      
    }
    public function delete(Request $request) {
        $category =  Category::findOrFail($request->id);
        $filePath  =  getFilePath('category') . '/' . $category->image;
        fileManager()->removeFile($filePath);
        $category->delete();
        $notify[] = ['success','Category has been deleted successfully'];
        return back()->withNotify($notify);
    }
}
