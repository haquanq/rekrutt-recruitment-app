<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalDocumentRequest;

class UpdateProposalDocumentRequest extends BaseProposalDocumentRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        echo json_encode($rules["note"]);
        return ["note" => $rules["note"]];
    }
}
