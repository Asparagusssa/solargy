<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patent\PatentStoreRequest;
use App\Http\Requests\Patent\PatentUpdateRequest;
use App\Http\Resources\Patent\PatentResource;
use App\Models\Patent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Patent::orderBy('id')->paginate(4);
        return response()->json(PatentResource::collection($data), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatentStoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('patents', 'public');
            $data['file'] = $filePath;
            $data['file_name'] = $request->file('file')->getClientOriginalName();
        }

        $patent = Patent::create($data);
        return response()->json(new PatentResource($patent), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patent $patent)
    {
        return response()->json(new PatentResource($patent), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatentUpdateRequest $request, Patent $patent)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            if ($patent->file) {
                Storage::disk('public')->delete($patent->file);
            }
            $filePath = $request->file('file')->store('patents', 'public');
            $data['file'] = $filePath;
            $data['file_name'] = $request->file('file')->getClientOriginalName();
        }

        $patent->update($data);
        return response()->json(new PatentResource($patent), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patent $patent)
    {
        if ($patent->file) {
            Storage::disk('public')->delete($patent->file);
        }
        $patent->delete();
        return response()->json(null, 204);
    }
}