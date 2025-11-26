<?php

namespace App\Modules\Candidate\Requests;

use App\Modules\Candidate\Abstracts\BaseCandidateDocumentRequest;

class UpdateCandidateDocumentRequest extends BaseCandidateDocumentRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        echo json_encode($rules["note"]);
        return ["note" => $rules["note"]];
    }
}
