<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index(){
        $pageTitle = 'Slider';
        $sliders = Slider::latest()->paginate(getPaginate(10));
        return view('admin.slider.index',compact('pageTitle','sliders'));
    }

    public function create()
    {
        $pageTitle = 'Slider Create';
        return view('admin.slider.create',compact('pageTitle'));
    }

    public function store(Request $request) {
        $request->validate(
        [
            'title' =>'required',
            'description' =>'',
            'link' =>'',
            'status' =>'required',
            'image' => ['required','image',new FileTypeValidate(['jpg','jpeg','png','gif','webp'])]
        ]);

        $slider = new Slider();
        $slider->title=$request->title;
        $slider->description=$request->description;
        $slider->link=$request->link;
        $slider->status=$request->status;

        if ($request->hasFile('image')) {
            try {
                $slider->image = fileUploader($request->image, getFilePath('slider'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $slider->save();
        $notify[] = ['success','Slider has been created successfully'];
        return back()->withNotify($notify);
        
    }

    public function edit($id){
        $pageTitle = 'Update';
        $slider = Slider::findOrFail($id);
        return view('admin.slider.edit',compact('pageTitle','slider'));
    }

    public function update(Request $request,$id){
        $request->validate(
        [
            'title' =>'required',
            'description' =>'',
            'link' =>'',
            'status' =>'required',
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png','gif','webp'])]
        ]);
  
        $slider =  Slider::findOrFail($id);
        $slider->title=$request->title;
        $slider->description=$request->description;
        $slider->link=$request->link;
        $slider->status=$request->status;
        
        if ($request->hasFile('image')) {
            try {
                $old = $slider->image;
                $slider->image = fileUploader($request->image, getFilePath('slider'),'',$old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $slider->save();
        $notify[] = ['success','Slider has been Updated successfully'];
        //return back()->withNotify($notify);
        return to_route('admin.slider.index')->withNotify($notify);
        
    }

    public function delete(Request $request) {
        $slider =  Slider::findOrFail($request->id);
        $filePath  =  getFilePath('slider') . '/' . $slider->image;
        fileManager()->removeFile($filePath);
        $slider->delete();
        $notify[] = ['success','Slider has been deleted successfully'];
        return back()->withNotify($notify);
    }
}
