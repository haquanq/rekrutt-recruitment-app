<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalRequest;

class UpdateProposalRequest extends BaseProposalRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), []);
    }
}
