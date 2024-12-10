<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainBanner\MainBannerStoreRequest;
use App\Http\Requests\MainBanner\MainBannerUpdateRequest;
use App\Http\Resources\Banner\MainBannerResource;
use App\Models\MainBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MainBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = MainBanner::with('product')->orderBy('order')->get();

        return response()->json(MainBannerResource::collection($banners), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MainBannerStoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
            $data['image'] = $imagePath;
        }

        $banner = MainBanner::create($data);

        $banner->load('product');

        return response()->json(new MainBannerResource($banner), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MainBanner $mainBanner)
    {
        return response()->json('Метод не поддерживается для данного запроса', 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MainBannerUpdateRequest $request, MainBanner $mainBanner)
    {

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($mainBanner->image) {
                Storage::disk('public')->delete('banners/' . basename($mainBanner->image));
            }

            $imagePath = $request->file('image')->store('banners', 'public');
            $data['image'] = $imagePath;
        }

        $mainBanner->update($data);

        $mainBanner->load('product');

        return response()->json(new MainBannerResource($mainBanner), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MainBanner $mainBanner)
    {
        $mainBanner->delete();

        if ($mainBanner->image) {
            Storage::disk('public')->delete('banners/' . basename($mainBanner->image));
        }

        return response()->json(null, 204);
    }
}
