<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Option\OptionStoreRequest;
use App\Http\Requests\Option\OptionUpdateRequest;
use App\Http\Resources\Option\OptionResource;
use App\Models\Option;
use App\Models\Value;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class OptionController extends Controller
{
    public function index()
    {
        $data = Option::with([
            'values' => function ($query) {
                $query->orderBy('id');
            },
        ])->orderBy('name')->get();

        return response()->json(OptionResource::collection($data));
    }

    public function show(Option $option)
    {
        return response()->json(new OptionResource($option));
    }

    public function store(OptionStoreRequest $request)
    {
        $data = $request->validated();

        $option = Option::create($data);

        $values = $data['values'] ?? [];

        foreach ($values as $value) {
            if (isset($value['from-library']) && isset($value['image-library'])) {
                $path = $value['image-library'];
                $imagePath = str_replace('/storage/', '', parse_url($path, PHP_URL_PATH));
            } else {
                $imagePath = isset($value['image']) && $value['image'] instanceof UploadedFile
                    ? $value['image']->store('optionValues', 'public')
                    : null;
            }
            $order = null;
            if (isset($value['order'])) {
                $order = $value['order'];
            }
            $option->values()->create([
                'value' => $value['value'],
                'price' => $value['price'],
                'image' => $imagePath,
                'order' => $order,
            ]);
        }

        return response()->json(new OptionResource($option), 201);
    }

    public function update(OptionUpdateRequest $request, Option $option)
    {
        $data = $request->validated();

        $option->update($data);

        $values = $data['values'] ?? [];
        foreach ($values as $valueData) {
            $valueData['id'] = $valueData['id'] ?? null;
            $value = $option->values()->find($valueData['id']);
            if ($value) {
                if (isset($valueData['from-library']) && isset($valueData['image-library'])) {
                    Storage::disk('public')->delete('optionValues/' . basename($value->image));
                    $path = $valueData['image-library'];
                    $imagePath = str_replace('/storage/', '', parse_url($path, PHP_URL_PATH));
                    $value->image = $imagePath;
                }
                if (isset($valueData['image']) && $valueData['image'] instanceof UploadedFile) {
                    Storage::disk('public')->delete('optionValues/' . basename($value->image));
                    $imagePath = $valueData['image']->store('optionValues', 'public');
                    $value->image = $imagePath;
                }
                $value->value = $valueData['value'] ?? $value->value;
                $value->price = $valueData['price'] ?? $value->price;
                $value->order = $value['order'] ?? $value->order;
                $value->save();
            } else {
                $order = null;
                if (isset($valueData['from-library']) && isset($valueData['image-library'])) {
                    $path = $valueData['image-library'];
                    $imagePath = str_replace('/storage/', '', parse_url($path, PHP_URL_PATH));
                }
                if (isset($valueData['image']) && $valueData['image'] instanceof UploadedFile) {
                    $imagePath = $valueData['image']->store('optionValues', 'public');
                }
                if (isset($valueData['order'])) {
                    $order = $valueData['order'];
                }
                $option->values()->create([
                    'value' => $valueData['value'],
                    'price' => $valueData['price'],
                    'order' => $order,
                    'image' => $imagePath ?? null
                ]);
            }
        }

        $option->load(['values' => function ($query) {
            $query->orderBy('id');
        }]);

        return response()->json(new OptionResource($option));
    }

    public function destroy(Option $option)
    {
        $values = $option->values;
        foreach ($values as $value) {
            if ($value->image) {
                Storage::disk('public')->delete('optionValues/' . basename($value->image));
            }
        }

        $option->delete();

        return response()->json(null, 204);
    }

    public function destroyValue($optionId, $valueId)
    {
        $value = Value::find($valueId);

        if (!$value) {
            return response()->json(['message' => 'Value not found'], 404);
        }

        if ($value->image) {
            Storage::disk('public')->delete('optionValues/' . basename($value->image));
        }

        $value->delete();

        return response()->json(null, 204);
    }
}
