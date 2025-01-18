<?php

namespace App\Http\Resources\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductOptionPriceResource extends JsonResource
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
            'price' => $this->price,
            'options_details' => $this->getOptionDetails(),
            'product' => new ProductResource(Product::find($this->product_id)),
        ];
    }
}
