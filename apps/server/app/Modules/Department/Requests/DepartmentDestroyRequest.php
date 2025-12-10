<?php

namespace App\Modules\Department\Requests;

use App\Modules\Department\Abstracts\BaseDepartmentRequest;
use App\Modules\Department\Models\Department;
use Illuminate\Support\Facades\Gate;

class DepartmentDestroyRequest extends BaseDepartmentRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", Department::class);
        return true;
    }
}
