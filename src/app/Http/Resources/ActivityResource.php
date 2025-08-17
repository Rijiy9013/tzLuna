<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (string)data_get($this, 'id'),
            'name' => (string)data_get($this, 'name'),
            'slug' => (string)data_get($this, 'slug'),
            'level' => (int)data_get($this, 'level'),
            'parent_id' => data_get($this, 'parent_id'),
            'children' => ActivityResource::collection($this->whenLoaded('children')),
        ];
    }
}
