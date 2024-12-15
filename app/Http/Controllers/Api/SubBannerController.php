<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubBanner\SubBannerStoreRequest;
use App\Http\Requests\SubBanner\SubBannerUpdateRequest;
use App\Http\Resources\Banner\SubBannerResource;
use App\Models\SubBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubBannerController extends Controller
{
    public function index()
    {
        $banners = SubBanner::query()->orderBy('id')->orderBy('order')->get();

        return response()->json(SubBannerResource::collection($banners), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubBannerStoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
            $data['image'] = $imagePath;
        }

        $banner = SubBanner::create($data);

        return response()->json(new SubBannerResource($banner), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SubBanner $subBanner)
    {
        return response()->json(new SubBannerResource($subBanner), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubBannerUpdateRequest $request, SubBanner $subBanner)
    {

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($subBanner->image) {
                Storage::disk('public')->delete('banners/' . basename($subBanner->image));
            }

            $imagePath = $request->file('image')->store('banners', 'public');
            $data['image'] = $imagePath;
        }

        $subBanner->update($data);

        return response()->json(new SubBannerResource($subBanner), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubBanner $subBanner)
    {
        if ($subBanner->image) {
            Storage::disk('public')->delete('banners/' . basename($subBanner->image));
        }
        $subBanner->delete();
        return response()->json(null, 204);
    }
}
