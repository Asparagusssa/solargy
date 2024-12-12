<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Page\PageSectionStoreRequest;
use App\Http\Requests\Page\PageSectionUpdateRequest;
use App\Http\Resources\Page\PageResource;
use App\Http\Resources\Page\PageSectionResource;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::with('sections')->get();

        return response()->json(PageResource::collection($pages), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PageSectionStoreRequest $request)
    {
        $data = $request->validated();

        for ($i = 0; $i < count($data['sections']); $i++) {
            if ($request->hasFile('sections.'.$i.'.image')) {
                $imagePath = $request->file('sections.'.$i.'.image')->store('pageSections', 'public');
                $data['sections'][$i]['image'] = $imagePath;
            }
        }

        $page = Page::find($data['id']);
        foreach ($data['sections'] as $sectionData) {
            $page->sections()->create($sectionData);
        }

        $page->load('sections');

        return response()->json(new PageResource($page), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return response()->json(new PageResource($page->load('sections')), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PageSectionUpdateRequest $request, Page $page)
    {
        $data = $request->validated();

        for ($i = 0; $i < count($data['sections']); $i++) {
            $data['sections'][$i]['page_id'] = $page->id;
            $sectionId = $data['sections'][$i]['id'] ?? null;
            if($sectionId){
                $pageSection = PageSection::find($sectionId);
                if(isset($data['sections'][$i]['image'])){
                    if ($pageSection->image) {
                        Storage::disk('public')->delete('pageSections/' . basename($pageSection->image));
                    }
                    $imagePath = $request->file('sections.'.$i.'.image')->store('pageSections', 'public');
                    $data['sections'][$i]['image'] = $imagePath;
                }
                $data['sections'][$i]['html'] = $data['sections'][$i]['html'] ?? $pageSection->html;
                $data['sections'][$i]['title'] = $data['sections'][$i]['title'] ?? $pageSection->title;

                $pageSection->update($data['sections'][$i]);
            } else {
                if($data['sections'][$i]['image']){
                    $imagePath = $request->file('sections.'.$i.'.image')->store('pageSections', 'public');
                    $data['sections'][$i]['image'] = $imagePath;
                }
                $data['sections'][$i]['html'] = $data['sections'][$i]['html'] ?? "пусто";
                $pageSection = PageSection::create($data['sections'][$i]);
            }
            $data['sections'][$i] = $pageSection;
        }

        $page->load('sections');
        return response()->json(new PageResource($page), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pageSection = PageSection::findOrFail($id);

        if ($pageSection->image) {
            Storage::disk('public')->delete('pageSections/' . basename($pageSection->image));
        }
        $pageSection->delete();
        return response()->json(null, 204);
    }
}
