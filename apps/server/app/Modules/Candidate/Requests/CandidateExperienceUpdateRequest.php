<?php

namespace App\Modules\Candidate\Requests;

use App\Modules\Candidate\Abstracts\BaseCandidateExperienceRequest;
use App\Modules\Candidate\Models\CandidateExperience;
use Illuminate\Support\Facades\Gate;

class CandidateExperienceUpdateRequest extends BaseCandidateExperienceRequest
{
    public CandidateExperience $candidateExperience;

    public function authorize(): bool
    {
        Gate::authorize("update", CandidateExperience::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->candidateExperience = CandidateExperience::with("candidate")->findOrFail($this->route("id"));
    }
}
