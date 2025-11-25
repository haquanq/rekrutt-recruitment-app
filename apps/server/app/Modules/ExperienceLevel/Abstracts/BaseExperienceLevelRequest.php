<?php

namespace App\Modules\ExperienceLevel\Abstracts;

use App\Abstracts\BaseFormRequest;

abstract class BaseExperienceLevelRequest extends BaseFormRequest
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
        ];
    }
}
