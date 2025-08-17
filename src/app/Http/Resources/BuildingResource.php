<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BuildingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (string)data_get($this, 'id'),
            'address' => (string)data_get($this, 'address'),
            'lat' => $this->lat ?? null,
            'lng' => $this->lng ?? null,
        ];
    }
}
