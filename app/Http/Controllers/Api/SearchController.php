<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke()
    {
        $q = mb_strtolower(request('q'));
        $product = Product::with(['photos', 'options', 'properties'])->whereRaw('lower(name) like ?', ["%{$q}%"])
            ->orderBy('name')
            ->get();

        return response()->json(ProductResource::collection($product), 200);
    }
}
