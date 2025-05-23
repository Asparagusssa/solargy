<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchasePlace\PurchasePlaceStoreRequest;
use App\Http\Requests\PurchasePlace\PurchasePlaceUpdateRequest;
use App\Http\Resources\PurchasePlace\PurchasePlaceResource;
use App\Models\PurchasePlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PurchasePlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $type = request('type');
        if (isset($type)) {
             $data =PurchasePlace::where('type', $type)->orderBy('id')->get();
             return response()->json(PurchasePlaceResource::collection($data), 200);
        }

        $data = PurchasePlace::orderBy('id')->get();

        return response()->json(PurchasePlaceResource::collection($data), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchasePlaceStoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('purchasePlaces', 'public');
            $data['image'] = $imagePath;
        }

        if ($request->hasFile('image_active')) {
            $imageActivePath = $request->file('image_active')->store('purchasePlaces', 'public');
            $data['image_active'] = $imageActivePath;
        }

        if ($request->hasFile('image_disable')) {
            $imageDisablePath = $request->file('image_disable')->store('purchasePlaces', 'public');
            $data['image_disable'] = $imageDisablePath;
        }

        $purchasePlace = PurchasePlace::create($data);

        return response()->json(new PurchasePlaceResource($purchasePlace), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchasePlace $purchasePlace)
    {
        return response()->json(new PurchasePlaceResource($purchasePlace), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchasePlaceUpdateRequest $request, PurchasePlace $purchasePlace)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($purchasePlace->image) {
                Storage::disk('public')->delete('purchasePlaces/' . basename($purchasePlace->image));
            }
            $imagePath = $request->file('image')->store('purchasePlaces', 'public');
            $data['image'] = $imagePath;
        }

        if ($request->hasFile('image_active')) {
            if ($purchasePlace->image_active) {
                Storage::disk('public')->delete('purchasePlaces/' . basename($purchasePlace->image_active));
            }
            $imageActivePath = $request->file('image_active')->store('purchasePlaces', 'public');
            $data['image_active'] = $imageActivePath;
        }

        if ($request->hasFile('image_disable')) {
            if ($purchasePlace->image_disable) {
                Storage::disk('public')->delete('purchasePlaces/' . basename($purchasePlace->image_disable));
            }
            $imageDisablePath = $request->file('image_disable')->store('purchasePlaces', 'public');
            $data['image_disable'] = $imageDisablePath;
        }

        $purchasePlace->update($data);

        return response()->json(new PurchasePlaceResource($purchasePlace), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchasePlace $purchasePlace)
    {
        if ($purchasePlace->image) {
            Storage::disk('public')->delete('purchasePlaces/' . basename($purchasePlace->image));
        }

        $purchasePlace->delete();

        return response()->json(null, 204);
    }
}
