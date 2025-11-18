<?php

namespace App\Modules\Auth\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;
use App\Rules\PhoneNumberRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum as EnumRule;
use Illuminate\Validation\Rules\Password as PasswordRule;

abstract class BaseUserRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = \intval($this->route("user"));
        return [
            "first_name" => ["required", "string", "max:100"],
            "last_name" => ["required", "string", "max:100"],
            "email" => ["required", "email", Rule::unique("user", "email")->ignore($id)],
            "username" => ["required", "string", "max:40"],
            "role" => ["required", new EnumRule(UserRole::class)],
            "phone_number" => ["required", new PhoneNumberRule(), "unique:user,phone_number,$id", "max:15"],
            "position_id" => ["required"],
        ];
    }
}
