<?php

namespace App\Modules\Candidate\Requests;

use App\Modules\Candidate\Abstracts\BaseCandidateRequest;

class UpdateCandidateRequest extends BaseCandidateRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        return array_merge($rules, []);
    }
}
