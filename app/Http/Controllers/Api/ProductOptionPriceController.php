<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductOptionPriceResource;
use App\Models\ProductOptionPrice;
use Illuminate\Http\Request;

class ProductOptionPriceController extends Controller
{
    public function index()
    {
        $prices = ProductOptionPrice::with('product')->get();
        return response()->json($prices);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'options' => 'required|json',
            'options.*' => 'integer|exists:values,id',
            'price' => 'required|numeric|min:0',
        ]);

        $price = ProductOptionPrice::create($validated);

        return response()->json($price, 201);
    }

    public function show($id)
    {
        $price = ProductOptionPrice::findOrFail($id);
        return response()->json([
            'id' => $price->id,
            'product_id' => $price->product_id,
            'options_details' => $price->getOptionDetails(),
            'price' => $price->price,
        ]);
    }

    public function update(Request $request, $id)
    {
        $price = ProductOptionPrice::findOrFail($id);

        $validated = $request->validate([
            'product_id' => 'sometimes|exists:products,id',
            'options' => 'sometimes|array',
            'options.*' => 'integer|exists:values,id',
            'price' => 'sometimes|numeric|min:0',
        ]);

        $price->update($validated);

        return response()->json(new ProductOptionPriceResource($price));
    }

    public function destroy($id)
    {
        $price = ProductOptionPrice::findOrFail($id);
        $price->delete();

        return response()->json(null, 204);
    }
}
