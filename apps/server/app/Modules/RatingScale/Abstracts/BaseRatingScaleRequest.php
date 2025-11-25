<?php

namespace App\Modules\RatingScale\Abstracts;

use App\Abstracts\BaseFormRequest;

abstract class BaseRatingScaleRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:100"],
            "description" => ["string", "max:500"],
            "is_active" => ["required", "boolean"],
        ];
    }
}
