<?php

namespace App\Modules\EducationLevel\Requests;

use App\Modules\EducationLevel\Abstracts\BaseEducationLevelRequest;
use App\Modules\EducationLevel\Models\EducationLevel;
use Illuminate\Support\Facades\Gate;

class EducationLevelDestroyRequest extends BaseEducationLevelRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", EducationLevel::class);
        return true;
    }
}
