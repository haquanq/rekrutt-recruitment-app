<?php

namespace App\Modules\ExperienceLevel\Requests;

use App\Modules\ExperienceLevel\Abstracts\BaseExperienceLevelRequest;
use App\Modules\ExperienceLevel\Models\ExperienceLevel;
use Illuminate\Support\Facades\Gate;

class ExperienceLevelStoreRequest extends BaseExperienceLevelRequest
{
    public function authorize(): bool
    {
        Gate::authorize("create", ExperienceLevel::class);
        return true;
    }
}
