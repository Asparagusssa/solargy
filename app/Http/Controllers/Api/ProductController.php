<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProductCopyAction;
use App\Actions\ValueCopyAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Http\Resources\Product\ProductAllResource;
use App\Http\Resources\Product\ProductResource;
use App\Models\Option;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\ProductProperty;
use App\Models\Value;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{

    public function productsForSelect()
    {
        return response()->json(ProductResource::collection(Product::orderBy('name')->get()));
    }

    public function getAllProducts(Request $request)
    {
        $categoryId = (int) $request->query('category');
        $minPrice = 0;
        $maxPrice = Product::max('price');

        if (request('minPrice')) {
            $minPrice = request('minPrice');
        }
        if (request('maxPrice')) {
            $maxPrice = request('maxPrice');
        }

        $query = Product::with([
            'photos' => function ($query) {
                $query->orderByRaw('"order" IS NULL, "order" ASC')->orderBy('id', 'ASC');
            },
            'options' => function ($query) {
                $query->orderBy('id');
            },
            'properties' => function ($query) {
                $query->orderBy('id');
            },
            'category' => function ($query) {
                $query->orderBy('id');
            }
        ])
            ->where('price', '>=', $minPrice)
            ->where('price', '<=', $maxPrice)
            ->orderBy('name');

        if (request('top')) {
            $isTop = (bool) request('top');
            $query = $query->where('is_top', $isTop);
        }
        if ($categoryId) {
            $query = $query->where('category_id', $categoryId);
        }
        $products = $query->paginate(10);

        return response()->json([
            'data' => ProductAllResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'path' => $products->path(),
            ],
        ], 200);
    }


    public function index(Request $request)
    {
        $categoryId = $request->query('category');
        $isTop = $request->query('top');

        $query = Product::with([
            'photos' => function ($query) {
                $query->orderByRaw('"order" IS NULL, "order" ASC')->orderBy('id', 'ASC');
            },
            'options' => function ($query) {
                $query->orderBy('id');
            },
            'properties' => function ($query) {
                $query->orderBy('id');
            },
            'category' => function ($query) {
                $query->orderBy('id');
            },
        ])->orderBy('name');

        if ($categoryId) {
            $products = $query->where('category_id', $categoryId)->paginate(8);
        } elseif ($isTop) {
            $products = $query->where('is_top', true)->orderBy('is_top')->paginate(4);

            if ($products->count() < 4) {
                $additionalProducts = Product::with([
                    'photos' => function ($query) {
                        $query->orderByRaw('"order" IS NULL, "order" ASC')->orderBy('id', 'ASC');
                    },
                    'options' => function ($query) {
                        $query->orderBy('id');
                    },
                    'properties' => function ($query) {
                        $query->orderBy('id');
                    },
                    'category' => function ($query) {
                        $query->orderBy('id');
                    },
                ])->where('is_top', false)->orderBy('is_top')->limit(4 - $products->count())->get();

                $products = $products->getCollection()->merge($additionalProducts)
                    ->values();

                return response()->json([
                    'data' => ProductResource::collection($products)
                ]);
            }
        } else {
            $products = $query->paginate(8);
        }

        return response()->json([
            'data' => ProductResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'path' => $products->path(),
            ],
        ], 200);
    }


    public function show(Product $product)
    {
        $order = ['description', 'property', 'photo', 'instruction', 'recommendation', 'guaranty'];

        $product->load([
            'photos' => function ($query) {
                $query->orderByRaw('"order" IS NULL, "order" ASC')->orderBy('id', 'ASC');
            },
            'options' => function ($query) use ($product) {
                $query->with(['values' => function ($query) use ($product) {
                    $query->whereHas('products', function ($query) use ($product) {
                        $query->where('product_id', $product->id);
                    })
                    ->orderByRaw("CASE WHEN value ~ '^[0-9]+(\.[0-9]+)?$' THEN NULLIF(value, '')::numeric ELSE NULL END NULLS LAST, value");
                }])
                ->orderBy('id');
            },
            'properties' => function ($query) {
                $query->orderBy('id');
            },
            'relatedProducts.photos' => function ($query) {
                $query->orderByRaw('"order" IS NULL, "order" ASC')->orderBy('id', 'ASC');
            },
            'optionPrices' => function ($query) {
                $query->orderBy('id');
            }
        ]);
        $product->options = $product->options->unique('id');

        $product->properties = $product->properties->sortBy(function ($property) use ($order) {
            return array_search($property->title, $order);
        })->values();

        $product->related_products = $product->relatedProducts->map(function ($relatedProduct) {
            $relatedProduct->photo = $relatedProduct->photos->first()?->photo ?? null;
            unset($relatedProduct->photos);
            return $relatedProduct;
        });

        return response()->json(new ProductResource($product));
    }

    public function store(ProductStoreRequest $request)
    {
        $order = ['property', 'description', 'photo', 'instruction', 'recommendation', 'guaranty'];

        $data = $request->validated();

        $product = Product::create($data);

        $photos = $data['photos'] ?? [];
        foreach ($photos as $photo) {
            $file = $photo['photo'];
            $fileType = $file->getMimeType();
            $isImage = str_starts_with($fileType, 'image/');
            $isVideo = str_starts_with($fileType, 'video/');
            $type = $isImage ? 'image' : ($isVideo ? 'video' : null);
            $filePath = $file->store("products", 'public');
            $product->photos()->create([
                'photo' => $filePath,
                'type' => $type,
                'order' => $photo['order'] ?? null,
            ]);
        }

        $options = $data['options'] ?? [];
        foreach ($options as $option) {
            $this->createOptions($option, $product);
        }

        $properties = $data['properties'] ?? [];
        foreach ($properties as $property) {

            $imagePath = isset($property['image']) && $property['image'] instanceof UploadedFile
                ? $property['image']->store('productPropertyImages', 'public')
                : null;

            $hasFile = isset($property['file']) && $property['file'] instanceof UploadedFile;

            $filePath = $hasFile
                ? $property['file']->store('productPropertyFiles', 'public')
                : null;

            $fileName = $hasFile ? $property['file']->getClientOriginalName() : null;
            if (isset($data['filename'])) {
                $fileName = $data['filename'];
            }

            $product->properties()->create([
                'title' => $property['title'],
                'html' => $property['html'],
                'file' => $filePath,
                'file_name' => $fileName,
                'image' => $imagePath,
            ]);
        }

        $product = Product::with('photos', 'options', 'properties')->find($product->id);

        $product->options = $product->options->unique('id');

        $product->load([
            'photos' => function ($query) {
                $query->orderByRaw('"order" IS NULL, "order" ASC')->orderBy('id', 'ASC');
            },
            'options' => function ($query) use ($product) {
                $query->with(['values' => function ($query) use ($product) {
                    $query->whereHas('products', function ($query) use ($product) {
                        $query->where('product_id', $product->id);
                    })
                    ->orderBy('value');
                }])
                ->orderBy('id');
            },
            'properties' => function ($query) {
                $query->orderBy('id');
            },
        ]);

        $product->options = $product->options->unique('id');

        $product->properties = $product->properties->sortBy(function ($property) use ($order) {
            return array_search($property->title, $order);
        })->values();

        return response()->json(new ProductResource($product), 201);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $order = ['property', 'description', 'photo', 'instruction', 'recommendation', 'guaranty'];

        $data = $request->validated();

        if ($request->input('options')) {
            $this->validateOptions($request->input('options'), $product->id);
        }

        $product->update($data);

        $photos = $data['photos'] ?? [];
        foreach ($photos as $photoData) {
            $photoData['id'] = $photoData['id'] ?? null;
            $photo = $product->photos()->find($photoData['id']);
            $filePath = null;
            $type = null;

            if ($photo) {
                if (isset($photoData['photo']) && $photoData['photo'] instanceof UploadedFile) {
                    $fileType = $photoData['photo']->getMimeType();
                    $isImage = str_starts_with($fileType, 'image/');
                    $isVideo = str_starts_with($fileType, 'video/');
                    $type = $isImage ? 'image' : ($isVideo ? 'video' : null);
                    Storage::disk('public')->delete('products/' . basename($photo->photo));
                    $filePath = $photoData['photo']->store('products', 'public');
                }
                if ($filePath !== null) {
                    $photo->photo = $filePath;
                }
                $photo->order = $photoData['order'] ?? $photo->order;
                $photo->type = $type;
                $photo->save();
            } else {
                $file = $photoData['photo'];
                $fileType = $file->getMimeType();
                $isImage = str_starts_with($fileType, 'image/');
                $isVideo = str_starts_with($fileType, 'video/');
                $type = $isImage ? 'image' : ($isVideo ? 'video' : null);
                $filePath = $file->store("products", 'public');
                $product->photos()->create([
                    'photo' => $filePath,
                    'type' => $type,
                    'order' => $photoData['order'] ?? null,
                ]);
            }
        }

        $options = $data['options'] ?? [];
        foreach ($options as $optionData) {
            $optionData['id'] = $optionData['id'] ?? null;
            $option = Option::find($optionData['id']);
            if ($option) {
                $values = $optionData['values'] ?? [];
                if ($values == [] && empty($optionData['name'])){
                    foreach ($option->values as $val) {
                        $product->options()->attach($option->id, ['value_id' => $val->id]);
                    }
                }

                foreach ($values as $valueData) {
                    $valueData['id'] = $valueData['id'] ?? null;
                    $value = Value::find($valueData['id']);
                    if ($value) {
                        if (isset($valueData['image']) && $valueData['image'] instanceof UploadedFile) {
                            Storage::disk('public')->delete('optionValues/' . basename($value->image));
                            $imagePath = $valueData['image']->store('optionValues', 'public');
                            $value->image = $imagePath;
                        }
                        $value->value = $valueData['value'] ?? $value->value;
                        $value->price = $valueData['price'] ?? $value->price;
                        $value->save();
                        $product->options()->attach($option->id, ['value_id' => $value->id]);

                    } else {
                        if (isset($valueData['image']) && $valueData['image'] instanceof UploadedFile) {
                            $imagePath = $valueData['image']->store('products', 'public');
                        }
                        $newValue = $option->values()->create([
                            'value' => $valueData['value'],
                            'price' => $valueData['price'],
                            'image' => $imagePath ?? null
                        ]);
                        $product->options()->attach($option->id, ['value_id' => $newValue->id]);
                    }
                }
                $option->name = $optionData['name'] ?? $option->name;
                $option->save();
            } else {
                $this->createOptions($optionData, $product);
            }
        }

        $properties = $data['properties'] ?? [];
        foreach ($properties as $propertyData) {
            $propertyData['id'] = $propertyData['id'] ?? null;
            $property = $product->properties()->find($propertyData['id']);
            if ($property) {
                if (isset($propertyData['from-library']) && isset($propertyData['image-library'])) {
                    Storage::disk('public')->delete('productPropertyImages/' . basename($property->image));
                    $path = $propertyData['image-library'];
                    $imagePath = str_replace('/storage/', '', parse_url($path, PHP_URL_PATH));
                    $property->image = $imagePath;
                }
                if (isset($propertyData['image']) && $propertyData['image'] instanceof UploadedFile) {
                    Storage::disk('public')->delete('productPropertyImages/' . basename($property['image']));
                    $imagePath = $propertyData['image']->store('productPropertyImages', 'public');
                    $property->image = $imagePath;
                }
                $hasFile = isset($propertyData['file']) && $propertyData['file'] instanceof UploadedFile;
                $fileUpload = false;
                if (isset($propertyData['from-library']) && isset($propertyData['file-library'])) {
                    Storage::disk('public')->delete('productPropertyFiles/' . basename($property['file']));
                    $path = $propertyData['file-library'];
                    $filePath = str_replace('/storage/', '', parse_url($path, PHP_URL_PATH));
                    $property->file = $filePath;
                    $fileUpload = true;
                }
                if ($hasFile) {
                    Storage::disk('public')->delete('productPropertyFiles/' . basename($property['file']));
                    $filePath = $propertyData['file']->store('productPropertyFiles', 'public');
                    $property->file = $filePath;
                    $fileUpload = true;
                }
                if (isset($propertyData['filename']) && isset($property->file)) {
                    $property->file_name = $propertyData['filename'];
                }else if($fileUpload) {
                    $property->file_name = $propertyData['file']->getClientOriginalName();
                }
                $property->title = $propertyData['title'] ?? $property->title;
                $property->html = $propertyData['html'] ?? $property->html;

                $property->save();
            } else {
                $imagePath = isset($propertyData['image']) && $propertyData['image'] instanceof UploadedFile
                    ? $propertyData['image']->store('productPropertyImages', 'public')
                    : null;
                $filePath = null;
                if (isset($propertyData['from-library']) && isset($propertyData['file-library'])) {
                    $path = $propertyData['file-library'];
                    $filePath = str_replace('/storage/', '', parse_url($path, PHP_URL_PATH));
                }

                $hasFile = isset($propertyData['file']) && $propertyData['file'] instanceof UploadedFile;
                if($hasFile) {
                    $filePath = $propertyData['file']->store('productPropertyFiles', 'public');
                }

                $fileName = $hasFile ? $propertyData['file']->getClientOriginalName() : null;
                if (isset($propertyData['filename'])) {
                    $fileName = $propertyData['filename'];
                }

                $title = $propertyData['title'] ?? null;
                $html = $propertyData['html'] ?? null;

                if (empty($title) || empty($html)) {
                    throw ValidationException::withMessages([
                        'option' => ['Поля "properties[title]" и "properties[html]" должно быть заполнено если не указан id изменяемого свойства.']
                    ]);
                }

                $product->properties()->create([
                    'title' => $propertyData['title'],
                    'html' => $propertyData['html'],
                    'file' => $filePath,
                    'file_name' => $fileName,
                    'image' => $imagePath,
                ]);
            }
        }

        $product->load([
            'photos' => function ($query) {
                $query->orderByRaw('"order" IS NULL, "order" ASC')->orderBy('id', 'ASC');
            },
            'options' => function ($query) use ($product) {
                $query->with(['values' => function ($query) use ($product) {
                    $query->whereHas('products', function ($query) use ($product) {
                        $query->where('product_id', $product->id);
                    })
                    ->orderBy('value');
                }])
                ->orderBy('id');
            },
            'properties' => function ($query) {
                $query->orderBy('id');
            },
        ]);
        $product->options = $product->options->unique('id');

        $product->properties = $product->properties->sortBy(function ($property) use ($order) {
            return array_search($property->title, $order);
        })->values();
        return response()->json(new ProductResource($product), 200);
    }

    public function destroy(Product $product)
    {
        $photos = $product->photos;
        foreach ($photos as $photo) {
            if ($photo->photo) {
                Storage::disk('public')->delete('products/' . basename($photo->photo));
            }
        }

        $properties = $product->properties;
        foreach ($properties as $property) {
            if ($property->image) {
                Storage::disk('public')->delete('productPropertyImages/' . basename($property->image));
            }
            if ($property->file) {
                Storage::disk('public')->delete('productPropertyFiles/' . basename($property->file));
            }
        }

        $product->delete();

        return response()->json(null, 204);
    }

    private function createOptions($optionData, $product)
    {
        $createdOption = Option::create([
            'name' => $optionData['name'],
        ]);

        $values = $optionData['values'] ?? [];

        foreach ($values as $value) {
            $imagePath = isset($value['image']) && $value['image'] instanceof UploadedFile
                ? $value['image']->store('optionValues', 'public')
                : null;
            $value['price'] = $value['price'] ?? 0;
            $createdValue = Value::create([
                'option_id' => $createdOption->id,
                'value' => $value['value'],
                'price' => $value['price'],
                'image' => $imagePath,
            ]);

            $product->options()->attach($createdOption->id, ['value_id' => $createdValue->id]);
        }
    }

    public function validateOptions($options, $productId)
    {
        foreach ($options as $option) {
            if (empty($option['id'])) {
                $hasValue = false;
                if (isset($option['values']) && is_array($option['values'])) {
                    foreach ($option['values'] as $value) {
                        if (!empty($value['value'])) {
                            $hasValue = true;
                            break;
                        }
                    }
                }
                if (!$hasValue) {
                    throw ValidationException::withMessages([
                        'option' => ['Поле "id" или "values[value]" должно быть заполнено.']
                    ]);
                }
            }
        }
    }

    public function deletePhotos($photoId)
    {
        $photo = ProductPhoto::find($photoId);
        if($photo->photo) {
            Storage::disk('public')->delete('products/' . basename($photo->photo));
        }
        $photo->delete();

        return response()->json(null, 204);
    }

    public function deletePivot($productId, $valueId)
    {
        $product = Product::findOrFail($productId);
        $value = Value::findOrFail($valueId);

        $data = $product->values()->detach($value->id);

        return response()->json($data);
    }

    public function deleteAllPivot($productId, $optionId)
    {
        $product = Product::findOrFail($productId);
        $option = Option::findOrFail($optionId);

        $values = $product->values()->wherePivot('option_id', $option->id)->get();

        if ($values->isEmpty()) {
            return response()->json(['message' => 'Нет привязанных значний опции'], 404);
        }

        foreach ($values as $value) {
            $product->values()->detach($value->id);
        }

        return response()->json(['message' => 'Все значения опции отвязаны'], 200);

    }

    public function deleteProperty($propertyId)
    {
        $property = ProductProperty::find($propertyId);

        if($property->image) {
            Storage::disk('public')->delete('productPropertyImages' . basename($property->image));
        }

        if($property->file) {
            Storage::disk('public')->delete('productPropertyFile' . basename($property->file));
        }

        $property->delete();

        return response()->json(null, 204);
    }

    public function addRelatedProducts(Request $request, Product $product)
    {
        $request->validate([
            'related_product_ids' => ['required', 'array'],
            'related_product_ids.*' => ['exists:products,id'],
        ]);

        foreach ($request->related_product_ids as $relatedProductId) {
            if ($relatedProductId != $product->id) {
                $product->relatedProducts()->syncWithoutDetaching($relatedProductId);
            }
        }

        return response()->json([
            'message' => 'Related products added successfully.',
            'related_products' => $product->relatedProducts,
        ]);
    }

    public function removeRelatedProduct(Request $request, Product $product, Product $relatedProduct)
    {
        $product->relatedProducts()->detach($relatedProduct->id);

        return response()->json([
            'message' => 'Related product removed successfully.',
            'related_products' => $product->relatedProducts,
        ]);
    }

    public function copyProduct($product_id, ProductCopyAction $productCopyAction)
    {
        try {
            $newProduct = $productCopyAction($product_id);
            return response()->json('New product successfully copied', 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

    public function copyValue($value_id, ValueCopyAction $valueCopyAction)
    {
        try {
            $newValue = $valueCopyAction($value_id);
            return response()->json('New value successfully copied', 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

}
