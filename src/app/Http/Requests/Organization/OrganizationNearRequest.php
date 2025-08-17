<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationNearRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radius_m' => 'required|integer|min:1|max:100000',
            'per_page' => 'sometimes|integer|min:1|max:200',
        ];
    }
}
