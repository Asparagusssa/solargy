<?php

namespace App\Http\Resources\Product;

use AllowDynamicProperties;
use App\Http\Resources\Market\MarketResource;
use App\Http\Resources\Option\ProductOptionResource;
use App\Http\Resources\Promo\PromoProductResource;
use App\Http\Resources\Property\PropertyResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

#[AllowDynamicProperties]
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
            'options' => ProductOptionResource::collection($this->whenLoaded('options')),
            'properties' => PropertyResource::collection($this->whenLoaded('properties')),
            'related_products' => RelatedProductResource::collection($this->whenLoaded('relatedProducts')),
            'option_prices' => ProductOptionPriceResource::collection($this->whenLoaded('optionPrices')),
            'promos' => PromoProductResource::collection($this->whenLoaded('promos')),
            'markets' => MarketResource::collection($this->markets),
        ];
    }
}
