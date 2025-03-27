<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Promo\PromoStoreRequest;
use App\Http\Requests\Promo\PromoUpdateRequest;
use App\Http\Resources\Promo\PromoResource;
use App\Models\Product;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::orderBy('id')->get();
        return response()->json(PromoResource::collection($promos), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PromoStoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('promos', 'public');
            $data['image'] = $imagePath;
        }

        $promo = Promo::create($data);

        return response()->json(new PromoResource($promo), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Promo $promo)
    {
        return response()->json(new PromoResource($promo), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PromoUpdateRequest $request, Promo $promo)
    {
        $data = $request->validated();

        if (isset($data['start']) && isset($data['end'])) {
            if ($data['start'] >= $data['end']) {
                return response()->json(['message' => 'Дата начала акции должна быть раньше даты окончания'], 400);
            }
        } elseif (isset($data['start']) && !isset($data['end'])) {
            return response()->json(['message' => 'Поле "end" обязательно, если указано поле "start".'], 400);
        }


        if ($request->hasFile('image')) {
            if ($promo->image) {
                Storage::disk('public')->delete('promos/' . basename($promo->image));
            }
            $imagePath = $request->file('image')->store('promos', 'public');
            $data['image'] = $imagePath;
        }

        $promo->update($data);

        return response()->json(new PromoResource($promo), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promo $promo)
    {
        if ($promo->image) {
            Storage::disk('public')->delete('promos/' . basename($promo->image));
        }
        $promo->delete();
        return response()->json(null, 204);
    }

    public function addProduct(Promo $promo, Product $product)
    {
        $promo->products()->syncWithoutDetaching($product);
        $promo->load('products');
        return response()->json(new PromoResource($promo), 200);
    }

    public function removeProduct(Promo $promo, Product $product)
    {
        $promo->products()->detach($product);
        $promo->load('products');
        return response()->json(new PromoResource($promo), 200);
    }
}
