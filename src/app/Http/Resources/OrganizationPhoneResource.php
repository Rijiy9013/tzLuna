<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationPhoneResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (string)data_get($this, 'id'),
            'display' => (string)data_get($this, 'phone_display'),
            'normalized' => (string)data_get($this, 'phone_normalized'),
        ];
    }
}
