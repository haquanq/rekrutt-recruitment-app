<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalRequest;
use App\Modules\Proposal\Models\Proposal;
use Illuminate\Support\Facades\Gate;

class ProposalDestroyRequest extends BaseProposalRequest
{
    public Proposal $proposal;

    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", $this->proposal);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->proposal = Proposal::findOrFail($this->route("id"));
    }
}
