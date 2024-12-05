<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Option\ProductOptionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'is_top' => $this->is_top,
            'photos' => ProductPhotoResource::collection($this->whenLoaded('photos')),
            'options' => ProductOptionResource::collection($this->whenLoaded('options'))
        ];
    }
}
