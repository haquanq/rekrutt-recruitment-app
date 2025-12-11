<?php

namespace App\Modules\Candidate\Requests;

use App\Modules\Candidate\Abstracts\BaseCandidateExperienceRequest;
use App\Modules\Candidate\Models\CandidateExperience;
use Illuminate\Support\Facades\Gate;

class CandidateExperienceDestroyRequest extends BaseCandidateExperienceRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", CandidateExperience::class);
        return true;
    }
}
