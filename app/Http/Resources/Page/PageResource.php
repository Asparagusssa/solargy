<?php

namespace App\Http\Resources\Page;

use App\Http\Resources\Seo\SeoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Page */
class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,

            'sections' => PageSectionResource::collection($this->whenLoaded('sections')),
            'seos' => SeoResource::collection($this->whenLoaded('seos')),
        ];
    }
}
