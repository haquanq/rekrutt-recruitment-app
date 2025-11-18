<?php

namespace App\Modules\Auth\Requests;

use App\Abstracts\BaseFormRequest;

class LoginRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "email" => ["required", "email"],
            "password" => ["required", "string", "max:30"],
        ];
    }
}
