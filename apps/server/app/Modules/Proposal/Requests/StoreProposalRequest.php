<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalRequest;
use Illuminate\Support\Facades\Auth;

class StoreProposalRequest extends BaseProposalRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), []);
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->merge([
            "created_by_user_id" => Auth::user()->id,
        ]);
    }
}
