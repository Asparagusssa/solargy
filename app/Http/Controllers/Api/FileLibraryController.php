<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\FileLibraryRequest;
use App\Models\FileLibrary;
use Illuminate\Support\Facades\Storage;

class FileLibraryController extends Controller
{
    public function index()
    {
        $file = FileLibrary::query()->get(['id', 'file', 'file_name']);
        return response()->json($file);
    }

    public function show($file_id)
    {
        $fileLibrary = FileLibrary::query()->findOrFail($file_id);
        return response()->json($fileLibrary->only('id', 'file', 'file_name'));
    }

    public function store(FileLibraryRequest $request)
    {
        $data = $request->validated();

        $filePath = $request->file('file')->store('fileLibrary', 'public');
        $file = FileLibrary::query()->create([
            'file' => $filePath,
            'file_name' => $data->file_name,
        ]);

        return response()->json($file->only('id', 'file', 'file_name'));
    }

    public function update(FileLibraryRequest $request, $file_id)
    {
        $data = $request->validated();

        $fileLibrary = FileLibrary::findOrFail($file_id);
        Storage::disk('public')->delete('fileLibrary/' . basename($fileLibrary->file));
        $filePath = $request->file('file')->store('fileLibrary', 'public');

        $data['file'] = $filePath;
        $fileLibrary->update($data);

        return response()->json($fileLibrary->only('id', 'file', 'file_name'));
    }

    public function destroy($file_id)
    {
        $fileLibrary = FileLibrary::findOrFail($file_id);
        Storage::disk('public')->delete('fileLibrary/' . basename($fileLibrary->file));
        $fileLibrary->delete();

        return response()->json(null, 204);
    }
}
