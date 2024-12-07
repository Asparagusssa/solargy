<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Option;
use App\Models\Product;
use App\Models\Value;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{

    public function index(Request $request)
    {

        $categoryId = $request->query('category');
        $isTop = $request->query('top');

        if($categoryId) {
            $products = Product::with('photos', 'options.values')->orderBy('id')->where('category_id', $categoryId)->paginate(8);
        }else if($isTop) {
            $products = Product::with('photos', 'options.values')->orderBy('id')->where('is_top', true)->paginate(4);
            if($products->count() < 4) {
                $additionalProducts = Product::with('photos', 'options.values')->orderBy('id')->where('is_top', false)->limit(4 - $products->count())->get();
                $products = $products->merge($additionalProducts);
            }
        }else{
            $products = Product::with('photos', 'options.values')->orderBy('id')->paginate(8);
        }


        return response()->json(ProductResource::collection($products), 200);
    }

    public function show(Product $product)
    {
        $product->load('photos', 'options.values');

        return response()->json(new ProductResource($product));
    }

    public function store(ProductStoreRequest $request)
    {

        $data = $request->validated();

        $product = Product::create($data);

        $photos = $data['photos'] ?? [];
        foreach ($photos as $photo) {
            $imagePath = $photo['photo']->store('products', 'public');
            $product->photos()->create([
                'photo' => $imagePath,
                'order' => $photo['order'] ?? null
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

            $filePath = isset($property['file']) && $property['file'] instanceof UploadedFile
                ? $property['file']->store('productPropertyFiles', 'public')
                : null;

            $product->properties()->create([
                'title' => $property['title'],
                'html' => $property['html'],
                'file' => $filePath,
                'image' => $imagePath,
            ]);
        }

        $product = Product::with('photos', 'options', 'properties')->find($product->id);

        $product->options = $product->options->unique('id');


        return response()->json(new ProductResource($product), 201);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->input('options')) {
            $this->validateOptions($request->input('options'), $product->id);
        }

        $product->update($data);

        $photos = $data['photos'] ?? [];
        foreach ($photos as $photoData) {
            $photoData['id'] = $photoData['id'] ?? null;
            $photo = $product->photos()->find($photoData['id']);
            if ($photo) {
                Storage::disk('public')->delete($photo['photo']);
                $imagePath = $photoData['photo']->store('products', 'public');
                $photo->photo = $imagePath ?? $photo->photo;
                $photo->order = $photoData['order'] ?? null;
                $photo->save();
            } else {
                $imagePath = $photoData['photo']->store('products', 'public');
                $product->photos()->create([
                    'photo' => $imagePath,
                    'order' => $photoData['order'] ?? null
                ]);
            }
        }

        $options = $data['options'] ?? [];
        foreach ($options as $optionData) {
            $optionData['id'] = $optionData['id'] ?? null;
            $option = $product->options()->find($optionData['id']);
            if ($option) {
                $values = $optionData['values'] ?? [];
                foreach ($values as $valueData) {
                    $valueData['id'] = $valueData['id'] ?? null;
                    $value = $option->values()->find($valueData['id']);
                    if ($value) {
                        if (isset($valueData['image']) && $valueData['image'] instanceof UploadedFile) {
                            Storage::disk('public')->delete($value['image']);
                            $imagePath = $valueData['image']->store('optionValues', 'public');
                            $value->image = $imagePath;
                        }
                        $value->value = $valueData['value'] ?? $value->value;
                        $value->price = $valueData['price'] ?? $value->price;
                        $value->save();
                    } else {
                        if (isset($valueData['image']) && $valueData['image'] instanceof UploadedFile) {
                            $imagePath = $valueData['image']->store('products', 'public');
                        }
                        $option->values()->create([
                            'value' => $valueData['value'],
                            'price' => $valueData['price'],
                            'image' => $imagePath ?? null
                        ]);
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
                if (isset($propertyData['image']) && $propertyData['image'] instanceof UploadedFile) {
                    isset($property['image']) ?? Storage::disk('public')->delete($property['image']);
                    $imagePath = $propertyData['image']->store('productPropertyImages', 'public');
                    $property->image = $imagePath;
                }
                if (isset($propertyData['file']) && $propertyData['file'] instanceof UploadedFile) {
                        isset($property['file']) ?? Storage::disk('public')->delete($property['file']);
                    $filePath = $propertyData['file']->store('productPropertyFiles', 'public');
                    $property->file = $filePath;
                }
                $property->title = $propertyData['title'] ?? $property->title;
                $property->html = $propertyData['html'] ?? $property->html;

                $property->save();
            } else {
                $imagePath = isset($propertyData['image']) && $propertyData['image'] instanceof UploadedFile
                    ? $propertyData['image']->store('productPropertyImages', 'public')
                    : null;

                $filePath = isset($propertyData['file']) && $propertyData['file'] instanceof UploadedFile
                    ? $propertyData['file']->store('productPropertyFiles', 'public')
                    : null;

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
                    'image' => $imagePath,
                ]);
            }
        }



        $product->load(['photos' => function ($query) {
            $query->orderBy('order', 'asc');
        }]);

        $product->load(['options' => function ($query) {
            $query->orderBy('id', 'asc');
        }]);

        $product->load(['properties' => function ($query) {
            $query->orderBy('id', 'asc');
        }]);
        return response()->json(new ProductResource($product), 200);
    }

    public function destroy(Product $product)
    {
        $photos = $product->photos;
        foreach ($photos as $photo) {
            if ($photo->photo) {
                Storage::disk('public')->delete($photo->photo);
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
            } else {
                $exists = DB::table('option_values')
                    ->where('option_id', $option['id'])
                    ->where('product_id', $productId)
                    ->exists();

                if (!$exists) {
                    throw ValidationException::withMessages([
                        'option' => ['Поле "id" должно существовать в таблице options и быть привязано к редактируемому товару.']
                    ]);
                }
            }
        }
    }
}
