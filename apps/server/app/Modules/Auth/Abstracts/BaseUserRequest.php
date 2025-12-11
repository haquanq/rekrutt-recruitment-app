<?php

namespace App\Modules\Auth\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;
use App\Rules\PhoneNumberRule;
use Illuminate\Validation\Rule;

abstract class BaseUserRequest extends BaseFormRequest
{
    protected ?User $user = null;

    public function getQueriedUserOrFail(string $param = "id"): User
    {
        if ($this->user === null) {
            $this->user = User::findOrFail($this->route($param));
        }

        return $this->user;
    }

    public function rules(): array
    {
        return [
            /**
             * First name
             * @example John
             */
            "first_name" => ["required", "string", "max:100"],
            /**
             * Last name
             * @example Rockstar
             */
            "last_name" => ["required", "string", "max:100"],
            /**
             * Role
             * @example RECRUITER
             */
            "role" => ["required", Rule::enum(UserRole::class)],
            /**
             * Username
             * @example johnxrockstar01
             */
            "username" => [
                "bail",
                "required",
                "string",
                "max:40",
                Rule::unique("user", "username")->ignore($this->route("id")),
            ],
            /**
             * Email address
             * @example john.rockstar@gmail.com
             */
            "email" => ["bail", "required", "email", Rule::unique("user", "email")->ignore($this->route("id"))],
            /**
             * Phone number
             * @example +123456789
             */
            "phone_number" => [
                "bail",
                "required",
                new PhoneNumberRule(),
                Rule::unique("user", "phone_number")->ignore($this->route("id")),
            ],
            /**
             * Id of Position inside organization
             * @var integer
             * @example 1
             */
            "position_id" => ["bail", "required", "integer:strict", Rule::exists("position", "id")],
        ];
    }
}
