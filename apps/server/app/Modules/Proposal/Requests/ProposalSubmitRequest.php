<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalRequest;
use App\Modules\Proposal\Enums\ProposalStatus;
use App\Modules\Proposal\Rules\ProposalStatusTransitionsFromRule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ProposalSubmitRequest extends BaseProposalRequest
{
    public function authorize(): bool
    {
        Gate::authorize("submit", $this->proposal);
        return true;
    }

    public function rules(): array
    {
        return [
            /**
             * Status === PENDING
             * @ignoreParam
             */
            "status" => [
                "required",
                Rule::enum(ProposalStatus::class)->only(ProposalStatus::PENDING),
                new ProposalStatusTransitionsFromRule($this->getQueriedProposalOrFail()->status),
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            "status" => ProposalStatus::PENDING->value,
        ]);
    }
}
