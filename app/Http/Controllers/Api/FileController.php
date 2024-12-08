<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patent;
use App\Models\ProductProperty;

class FileController extends Controller
{
    public function download($path)
    {
        $property = ProductProperty::where('file', $path)->first();
        $patent = Patent::where('file', $path)->first();

        switch (true) {
            case $property:
                $originalFileName = $property->file_name;
                break;
            case $patent:
                $originalFileName = $patent->file_name;
                break;
            default:
                return response()->json(['message' => 'Файл не найден'], 404);
        }

        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath)) {
            return response()->json(['message' => 'Файл не найден'], 404);
        }

        return response()->download($fullPath, $originalFileName);
    }
}
