<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seo\SeoStoreRequest;
use App\Http\Requests\Seo\SeoUpdateRequest;
use App\Http\Resources\Page\PageResource;
use App\Http\Resources\Seo\SeoResource;
use App\Models\Page;
use App\Models\seo;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Page::with([
            'seos' => function ($query) {
                $query->orderBy('id');
            }
        ])->orderBy('id')->get();

        return response()->json(PageResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SeoStoreRequest $request)
    {
        $data = $request->validated();

        $seo = seo::create($data);

        return response()->json(new PageResource($seo));
    }

    /**
     * Display the specified resource.
     */
    public function show($pageId)
    {
        $data = Page::with([
                'seos' => function ($query) {
                    $query->orderBy('id');
                }
            ])->orderBy('id')->findOrFail($pageId);

        return response()->json(new PageResource($data));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SeoUpdateRequest $request, Seo $seo)
    {
        $data = $request->validated();

        $seo->update($data);

        return response()->json(new SeoResource($seo));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seo $seo)
    {
        $seo->delete();
        return response()->json(null, 204);
    }
}
