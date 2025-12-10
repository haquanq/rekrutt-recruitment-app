<?php

namespace App\Modules\ExperienceLevel\Requests;

use App\Modules\ExperienceLevel\Abstracts\BaseExperienceLevelRequest;
use App\Modules\ExperienceLevel\Models\ExperienceLevel;
use Illuminate\Support\Facades\Gate;

class ExperienceLevelDestroyRequest extends BaseExperienceLevelRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", ExperienceLevel::class);
        return true;
    }
}
