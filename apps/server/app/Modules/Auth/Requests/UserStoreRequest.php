<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Auth\Abstracts\BaseUserRequest;
use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\Gate;

class UserStoreRequest extends BaseUserRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules["status"]);
        return $rules;
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
        ]);
    }
}
