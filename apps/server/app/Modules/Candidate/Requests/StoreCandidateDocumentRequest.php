<?php

namespace App\Modules\Candidate\Requests;

use App\Modules\Candidate\Abstracts\BaseCandidateDocumentRequest;

class StoreCandidateDocumentRequest extends BaseCandidateDocumentRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), []);
    }
}
