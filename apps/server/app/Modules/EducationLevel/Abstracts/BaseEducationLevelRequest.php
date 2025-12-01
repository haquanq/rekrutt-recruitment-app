<?php

namespace App\Modules\EducationLevel\Abstracts;

use App\Abstracts\BaseFormRequest;

abstract class BaseEducationLevelRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            /**
             * Name
             * @example High School
             */
            "name" => ["required", "string", "max:100"],
            /**
             * Description
             * @example Graduated high school (no further education)
             */
            "description" => ["string", "max:500"],
        ];
    }
}
