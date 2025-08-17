<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (string)data_get($this, 'id'),
            'name' => (string)data_get($this, 'name'),
            'building' => BuildingResource::make(data_get($this, 'building')),
            'phones' => OrganizationPhoneResource::collection(collect(data_get($this, 'phones', []))),
            'activities' => ActivityResource::collection(collect(data_get($this, 'activities', []))),
            'created_at' => optional(data_get($this, 'created_at'))->toISOString() ?? null,
            'updated_at' => optional(data_get($this, 'updated_at'))->toISOString() ?? null,
        ];
    }
}
