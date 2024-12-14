<?php

namespace App\Http\Resources\Patent;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatentResource extends JsonResource
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
            'date' => $this->date instanceof Carbon ? $this->date->format('d.m.Y') : Carbon::parse($this->date)->format('d.m.Y'),
            'file' => $this->file,
            'file_name' => $this->file_name,
        ];
    }
}
