<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalRequest;
use App\Modules\Proposal\Enums\ProposalStatus;
use App\Modules\Proposal\Models\Proposal;
use App\Modules\Proposal\Rules\ProposalStatusTransitionsFromRule;
use Illuminate\Support\Facades\Gate;

class ProposalSubmitRequest extends BaseProposalRequest
{
    public Proposal $proposal;

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
            "status" => ["required", new ProposalStatusTransitionsFromRule($this->proposal->status)],
        ];
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->proposal = Proposal::findOrFail($this->route("id"));

        $this->merge([
            "status" => ProposalStatus::PENDING->value,
        ]);
    }
}
