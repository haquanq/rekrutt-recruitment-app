<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalDocumentRequest;

class StoreProposalDocumentRequest extends BaseProposalDocumentRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), []);
    }
}
