<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationUpdateRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'building_id' => 'sometimes|uuid|exists:buildings,id',
            'phones' => 'sometimes|array',
            'phones.*' => 'string|max:50',
            'activity_ids' => 'sometimes|array',
            'activity_ids.*' => 'uuid|exists:activities,id',
        ];
    }
}
