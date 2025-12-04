<?php

namespace App\Modules\Candidate\Requests;

use App\Modules\Candidate\Abstracts\BaseCandidateDocumentRequest;

class CandidateDocumentUpdateRequest extends BaseCandidateDocumentRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules["document"]);
        return $rules;
    }
}
