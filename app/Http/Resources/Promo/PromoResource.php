<?php

namespace App\Http\Resources\Promo;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromoResource extends JsonResource
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
            'start' => $this->start instanceof Carbon ? $this->start->format('d.m.Y') : Carbon::parse($this->start)->format('d.m.Y'),
            'end' => $this->end instanceof Carbon ? $this->end->format('d.m.Y') : Carbon::parse($this->end)->format('d.m.Y'),
            'is_archived' => $this->is_archived
        ];
    }
}
