<?php

namespace App\Http\Resources\PurchasePlace;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchasePlaceResource extends JsonResource
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
            'url' => $this->url,
            'image' => $this->image,
            'image_active' => $this->image_active,
            'image_disable' => $this->image_disable,
        ];
    }
}
