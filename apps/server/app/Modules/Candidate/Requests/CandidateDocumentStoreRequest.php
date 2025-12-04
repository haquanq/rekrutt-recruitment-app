<?php

namespace App\Modules\Candidate\Requests;

use App\Modules\Candidate\Abstracts\BaseCandidateDocumentRequest;
use App\Modules\Candidate\Enums\CandidateStatus;
use App\Modules\Candidate\Rules\CandidateExistsWithStatusRule;

class CandidateDocumentStoreRequest extends BaseCandidateDocumentRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            "candidate_id" => ["required", "integer", new CandidateExistsWithStatusRule(CandidateStatus::PENDING)],
        ];
    }
}
