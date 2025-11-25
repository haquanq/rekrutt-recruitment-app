<?php

namespace App\Modules\Department\Abstracts;

use App\Abstracts\BaseFormRequest;

abstract class BaseDepartmentRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:100"],
            "description" => ["nullable", "string", "max:500"],
        ];
    }
}
