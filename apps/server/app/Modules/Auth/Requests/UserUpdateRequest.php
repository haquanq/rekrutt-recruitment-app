<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Auth\Abstracts\BaseUserRequest;
use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\Gate;

class UserUpdateRequest extends BaseUserRequest
{
    public User $user;

    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules["status"]);

        if ($this->method() == "PATCH") {
            foreach ($rules as $field => &$fieldRules) {
                array_splice($fieldRules, 0, \boolval($fieldRules[0] === "required"), "sometimes");
            }
        }
        return $rules;
    }

    public function authorize(): bool
    {
        Gate::authorize("update", User::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->user = User::findOrFail($this->route("id"));
    }
}
