<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Auth\Abstracts\BaseUserRequest;
use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\Gate;

class UserDestroyRequest extends BaseUserRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", User::class);
        return true;
    }
}
