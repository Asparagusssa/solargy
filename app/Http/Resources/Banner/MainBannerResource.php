<?php

namespace App\Http\Resources\Banner;

use App\Http\Resources\Product\ProductMainBannerResource;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MainBannerResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'product' => new ProductMainBannerResource($this->whenLoaded('product')),
        ];
    }
}
