<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalRequest;
use App\Modules\Proposal\Enums\ProposalStatus;
use App\Modules\Proposal\Models\Proposal;
use App\Modules\Proposal\Rules\ProposalStatusTransitionsFromRule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ProposalDestroyRequest extends BaseProposalRequest
{
    public Proposal $proposal;

    public function rules(): array
    {
        Log::info("rules");
        return [];
    }

    public function authorize(): bool
    {
        Log::info("authorize");
        Gate::authorize("delete", $this->proposal);
        return true;
    }

    public function prepareForValidation(): void
    {
        Log::info("prepare");
        parent::prepareForValidation();
        $this->proposal = Proposal::findOrFail($this->route("id"));
    }
}
