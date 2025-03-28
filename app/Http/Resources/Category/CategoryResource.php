<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'photo' => $this->photo,
            'level' => $this->level,
            'parent' => new CategoryParentResource($this->parent),
            'children' => CategoryChildrenResource::collection($this->children),
        ];
    }
}
