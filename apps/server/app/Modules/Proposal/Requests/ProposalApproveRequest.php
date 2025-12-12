<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalRequest;
use App\Modules\Proposal\Enums\ProposalStatus;
use App\Modules\Proposal\Models\Proposal;
use App\Modules\Proposal\Rules\ProposalStatusTransitionsFromRule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ProposalApproveRequest extends BaseProposalRequest
{
    public function authorize(): bool
    {
        Gate::authorize("approve", Proposal::class);
        return true;
    }

    public function rules(): array
    {
        return [
            /**
             * Status === APPROVED
             * @ignoreParam
             */
            "status" => [
                "required",
                Rule::enum(ProposalStatus::class)->only(ProposalStatus::APPROVED),
                new ProposalStatusTransitionsFromRule($this->getQueriedProposalOrFail()->status),
            ],
            /**
             * Review timestamp (generated automatically)
             * @ignoreParam
             */
            "reviewed_at" => ["required", "date"],
            /**
             * Id of Reviewer (generated automatically)
             * @ignoreParam
             */
            "reviewed_by_user_id" => ["required", "integer:strict"],
            /**
             * Reviewer notes
             * @example
             */
            "reviewed_notes" => ["required", "string", "max:500"],
        ];
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            "status" => ProposalStatus::APPROVED->value,
            "reviewed_at" => Carbon::now(),
            "reviewed_by_user_id" => Auth::user()->id,
        ]);
    }
}
