<?php

namespace App\Modules\EducationLevel\Requests;

use App\Modules\EducationLevel\Abstracts\BaseEducationLevelRequest;
use App\Modules\EducationLevel\Models\EducationLevel;
use Illuminate\Support\Facades\Gate;

class EducationLevelUpdateRequest extends BaseEducationLevelRequest
{
    public function authorize(): bool
    {
        Gate::authorize("update", EducationLevel::class);
        return true;
    }
}
