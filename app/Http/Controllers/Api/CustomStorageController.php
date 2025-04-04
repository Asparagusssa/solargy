<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CustomStorageController extends Controller
{
    public function __invoke($path)
    {
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $file = Storage::disk('public')->get($path) ?? null;
        $mime = Storage::disk('public')->mimeType($path) ?? null;

        if (is_null($file)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return response($file, 200)
            ->header('Content-Type', $mime)
            ->header('Access-Control-Allow-Origin', '*');
    }
}
