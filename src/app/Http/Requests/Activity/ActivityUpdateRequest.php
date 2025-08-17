<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActivityUpdateRequest extends FormRequest
{

    public function rules(): array
    {
        $routeActivity = $this->route('activity');
        $activityId = is_object($routeActivity) ? $routeActivity->id : $routeActivity;

        return [
            'name' => 'sometimes|string|max:255',
            'slug' => [
                'sometimes', 'string', 'max:255',
                Rule::unique('activities', 'slug')->ignore($activityId, 'id'),
            ],
            'parent_id' => 'nullable|uuid',
        ];
    }
}
