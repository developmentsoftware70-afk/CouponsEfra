<?php

namespace App\Models;

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomSection;
use App\Models\Frontend;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomSectionController extends Controller
{
    public function index()
    {
        $pageTitle = 'Custom Sections Builder';
        $customSections = CustomSection::latest()->paginate(getPaginate(15));
        return view('admin.frontend.custom_sections.index', compact('pageTitle', 'customSections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'layout_type' => 'required|in:card_grid,banner_slider,promo_box',
            'heading'     => 'nullable|string|max:255',
            'subheading'  => 'nullable|string|max:255',
        ]);

        $baseSlug = Str::slug($request->name, '_');
        $key = 'custom_' . $baseSlug;

        // Ensure unique key
        $count = 1;
        while (CustomSection::where('key', $key)->exists()) {
            $key = 'custom_' . $baseSlug . '_' . $count;
            $count++;
        }

        $section = CustomSection::create([
            'name'        => $request->name,
            'key'         => $key,
            'layout_type' => $request->layout_type,
            'heading'     => $request->heading ?: $request->name,
            'subheading'  => $request->subheading,
            'status'      => 1,
        ]);

        // Create initial content record in Frontend model
        $content = new Frontend();
        $content->data_keys = $key . '.content';
        $content->data_values = [
            'heading'    => $request->heading ?: $request->name,
            'subheading' => $request->subheading ?: '',
        ];
        $content->save();

        $notify[] = ['success', 'New Custom Section created successfully! You can now add items and place it in the Page Builder.'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $section = CustomSection::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:100',
            'layout_type' => 'required|in:card_grid,banner_slider,promo_box',
            'heading'     => 'nullable|string|max:255',
            'subheading'  => 'nullable|string|max:255',
        ]);

        $section->update([
            'name'        => $request->name,
            'layout_type' => $request->layout_type,
            'heading'     => $request->heading,
            'subheading'  => $request->subheading,
        ]);

        $content = Frontend::where('data_keys', $section->key . '.content')->first();
        if ($content) {
            $data = $content->data_values;
            $data->heading = $request->heading;
            $data->subheading = $request->subheading;
            $content->data_values = $data;
            $content->save();
        }

        $notify[] = ['success', 'Custom Section updated successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        $section = CustomSection::findOrFail($id);
        $section->status = $section->status ? 0 : 1;
        $section->save();

        $notify[] = ['success', 'Status changed successfully'];
        return back()->withNotify($notify);
    }

    public function destroy($id)
    {
        $section = CustomSection::findOrFail($id);
        $key = $section->key;

        // Clean up from frontends table
        Frontend::where('data_keys', 'like', $key . '.%')->delete();

        // Clean up from Page Builder secs column if present
        $pages = Page::all();
        foreach ($pages as $p) {
            if ($p->secs) {
                $secs = json_decode($p->secs, true) ?: [];
                if (in_array($key, $secs)) {
                    $newSecs = array_values(array_diff($secs, [$key]));
                    $p->secs = json_encode($newSecs);
                    $p->save();
                }
            }
        }

        $section->delete();

        $notify[] = ['success', 'Custom Section deleted successfully'];
        return back()->withNotify($notify);
    }
}
