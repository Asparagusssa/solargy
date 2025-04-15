<?php

namespace App\Http\Resources\News;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            'image' => $this->image,
            'video' => $this->video,
            'date' => Carbon::parse($this->date)->format('d.m.Y'),
            'type' => $this->type,
            'html' => $this->html,
        ];
    }
}
