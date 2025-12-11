<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Auth\Abstracts\BaseUserRequest;

class UserLoginRequest extends BaseUserRequest
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
