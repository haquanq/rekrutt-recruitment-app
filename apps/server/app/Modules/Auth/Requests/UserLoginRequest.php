<?php

namespace App\Modules\Auth\Requests;

use App\Abstracts\BaseFormRequest;

class UserLoginRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            /**
             * Email address
             * @example admin@gmail.com
             */
            "email" => ["required", "email"],
            /**
             * Passowrd (default: 12345678)
             * @example 12345678
             */
            "password" => ["required", "string", "max:30"],
        ];
    }
}
