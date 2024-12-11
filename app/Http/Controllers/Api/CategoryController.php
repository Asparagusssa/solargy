<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Http\Resources\Category\CategoryChildrenResource;
use App\Http\Resources\Category\CategoryParentResource;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')->orderBy('id')->get();

        foreach ($categories as $category) {
            $category->children = $category->children()->orderBy('id')->get();
        }

        foreach ($categories as $category) {
            $category->products = $category->products()->orderBy('id')->get();
        }

        return response()->json(CategoryChildrenResource::collection($categories), 200);
    }

    public function show(Category $category)
    {
        $category->with(['parent', 'children'])->get();

        $category->children = $category->children()->orderBy('id')->get();
        $category->parent;

        return response()->json(new CategoryResource($category), 200);
    }

    public function store(CategoryStoreRequest $request)
    {
        $data = $request->validated();

        if (isset($data['parent_id'])) {
            $parentCategory = Category::find($data['parent_id']);
            $data['level'] = $parentCategory ? $parentCategory->level + 1 : 0;
        } else {
            $data['level'] = 0;
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('categories', 'public');
        }

        $category = Category::create($data);

        return response()->json(new CategoryResource($category), 201);
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            if ($category->photo) {
                Storage::disk('public')->delete('categories/' . basename($category->photo));
            }
            $imagePath = $request->file('photo')->store('categories', 'public');
            $data['photo'] = $imagePath;
        }
        if (isset($data['parent_id'])) {
            $parentCategory = Category::find($data['parent_id']);
            $data['level'] = $parentCategory ? $parentCategory->level + 1 : 0;
        }else {
            $data['level'] = 0;
        }

        $category->update($data);

        return response()->json(new CategoryResource($category->fresh()), 200);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(null, 204);
    }
}
