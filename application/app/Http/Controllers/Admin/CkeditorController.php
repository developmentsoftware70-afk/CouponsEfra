<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CkeditorController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'upload' => 'required|file|mimes:jpg,jpeg,png,gif,webp,svg|max:5120',
        ]);

        try {
            // Use your project’s helper
            $filename = fileUploader($request->upload, getFilePath('ckEditor'));

            // Build public URL
            $url = asset(getFilePath('ckEditor') . '/' . $filename);
            return response()->json(['url' => $url], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => ['message' => 'Upload failed: ' . $e->getMessage()]
            ], 422);
        }
    }
}
