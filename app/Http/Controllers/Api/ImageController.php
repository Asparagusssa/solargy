<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('editor', 'public');
            $url = url('storage/' . $path);

            return response()->json([
                'success' => true,
                'file' => [
                    'url' => $url,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image uploaded.',
        ], 400);
    }
}

