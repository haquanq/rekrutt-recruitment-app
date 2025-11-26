<?php

namespace App\Modules\Candidate\Requests;

use App\Modules\Candidate\Abstracts\BaseCandidateRequest;

class StoreCandidateRequest extends BaseCandidateRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), []);
    }
}
