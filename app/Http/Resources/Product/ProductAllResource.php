<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Option\ProductOptionResource;
use App\Http\Resources\Property\PropertyResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => CategoryResource::make($this->whenLoaded('category')),
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'is_top' => $this->is_top,
            'photos' => ProductPhotoResource::collection($this->whenLoaded('photos')),
            'options' => ProductOptionResource::collection($this->whenLoaded('options')),
            'properties' => PropertyResource::collection($this->whenLoaded('properties')),
        ];
    }
}
