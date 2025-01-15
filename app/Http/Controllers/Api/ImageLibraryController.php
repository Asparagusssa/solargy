<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\LibraryStoreRequest;
use App\Http\Requests\Library\LibraryUpdateRequest;
use App\Models\ImageLibrary;
use Illuminate\Support\Facades\Storage;

class ImageLibraryController extends Controller
{
    public function index()
    {
        $image = ImageLibrary::query()->get(['id', 'image']);
        return response()->json($image);
    }

    public function show($imageLibrary_id)
    {
        $imageLibrary = ImageLibrary::query()->findOrFail($imageLibrary_id);
        return response()->json($imageLibrary->only('id', 'image'));
    }

    public function store(LibraryStoreRequest $request)
    {
        $data = $request->validated();

        $imagePath = $request->file('image')->store('library', 'public');
        $createdImage = ImageLibrary::query()->create([
            'image' => $imagePath,
        ]);

        return response()->json($createdImage->only('id', 'image'));
    }

    public function update(LibraryUpdateRequest $request, $imageLibrary_id)
    {
        $data = $request->validated();

        $imageLibrary = ImageLibrary::findOrFail($imageLibrary_id);
        Storage::disk('public')->delete('library/' . basename($imageLibrary->image));
        $imagePath = $request->file('image')->store('library', 'public');

        $data['image'] = $imagePath;
        $imageLibrary->update($data);

        return response()->json($imageLibrary->only('id', 'image'));
    }

    public function destroy($imageLibrary_id)
    {
        $imageLibrary = ImageLibrary::findOrFail($imageLibrary_id);
        Storage::disk('public')->delete('library/' . basename($imageLibrary->image));
        $imageLibrary->delete();

        return response()->json(null, 204);
    }
}
