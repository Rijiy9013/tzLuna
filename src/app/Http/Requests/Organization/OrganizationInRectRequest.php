<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationInRectRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'min_lat' => 'required|numeric',
            'min_lng' => 'required|numeric',
            'max_lat' => 'required|numeric',
            'max_lng' => 'required|numeric',
            'per_page' => 'sometimes|integer|min:1|max:200',
        ];
    }
}
