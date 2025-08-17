<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationsByActivityRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'per_page' => 'sometimes|integer|min:1|max:200',
        ];
    }
}
