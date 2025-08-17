<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationSearchRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'q' => 'required|string|min:2',
            'per_page' => 'sometimes|integer|min:1|max:200',
        ];
    }
}
