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
            /**
             * Name
             * @example Freelance
             */
            "name" => ["required", "string", "max:100"],
            /**
             * Description
             * @example Working as a freelancer
             */
            "description" => ["string", "max:500"],
        ];
    }
}
