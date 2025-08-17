<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationStoreRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'building_id' => 'required|uuid|exists:buildings,id',
            'phones' => 'sometimes|array',
            'phones.*' => 'string|max:50',
            'activity_ids' => 'sometimes|array',
            'activity_ids.*' => 'uuid|exists:activities,id',
        ];
    }
}
