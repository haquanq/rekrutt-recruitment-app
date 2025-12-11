<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Auth\Abstracts\BaseUserRequest;
use App\Modules\Auth\Enums\UserStatus;
use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;

class UserStoreRequest extends BaseUserRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            ...[
                /**
                 * Password (generated automatically)
                 * @ignoreParam
                 */
                "password" => ["required", PasswordRule::default()->max(30)],
                /**
                 * Status === ACTIVE
                 * @ignoreParam
                 */
                "status" => ["required", Rule::enum(UserStatus::class)->only(UserStatus::ACTIVE)],
            ],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("create", User::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->mergeIfMissing([
            "password" => "12345678",
            "status" => UserStatus::ACTIVE->value,
        ]);
    }
}
