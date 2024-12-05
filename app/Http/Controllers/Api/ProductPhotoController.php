<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductPhotoResource;
use App\Models\ProductPhoto;
use Illuminate\Http\Request;

class ProductPhotoController extends Controller
{
    public function index()
    {
        return ProductPhotoResource::collection(ProductPhoto::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([

        ]);

        return new ProductPhotoResource(ProductPhoto::create($data));
    }

    public function show(ProductPhoto $productPhoto)
    {
        return new ProductPhotoResource($productPhoto);
    }

    public function update(Request $request, ProductPhoto $productPhoto)
    {
        $data = $request->validate([

        ]);

        $productPhoto->update($data);

        return new ProductPhotoResource($productPhoto);
    }

    public function destroy(ProductPhoto $productPhoto)
    {
        $productPhoto->delete();

        return response()->json();
    }
}
