<?php

namespace App\Modules\Candidate\Requests;

use App\Modules\Candidate\Abstracts\BaseCandidateExperienceRequest;

class UpdateCandidateExperienceRequest extends BaseCandidateExperienceRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), []);
    }
}
