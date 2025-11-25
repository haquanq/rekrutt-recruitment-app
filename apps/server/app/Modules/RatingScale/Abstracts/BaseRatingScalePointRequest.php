<?php

namespace App\Modules\RatingScale\Abstracts;

use App\Abstracts\BaseFormRequest;

abstract class BaseRatingScalePointRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "rank" => ["required", "integer"],
            "label" => ["required", "string", "max:100"],
            "definition" => ["required", "string", "max:300"],
            "rating_scale_id" => ["required", "integer", "exists:rating_scale,id"],
        ];
    }
}
