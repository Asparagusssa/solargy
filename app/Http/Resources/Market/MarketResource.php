<?php

namespace App\Http\Resources\Market;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarketResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->type,
            'is_attached' => $this->is_attached ?? false,
            'image_active' => $this->image_active,
            'image_disable' => $this->image_disable,
            'url' => $this->url,
            'product_url' => $this->pivot->url ?? null,
        ];
    }
}
