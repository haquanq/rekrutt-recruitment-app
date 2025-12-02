<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Position\Rules\PositionExistsInCurrentUserDepartmentRule;
use App\Modules\Proposal\Abstracts\BaseProposalRequest;
use App\Modules\Proposal\Models\Proposal;
use Illuminate\Support\Facades\Gate;

class ProposalUpdateRequest extends BaseProposalRequest
{
    public Proposal $proposal;

    public function authorize(): bool
    {
        $this->proposal = Proposal::findOrFail($this->route("id"));
        Gate::authorize("update", $this->proposal);
        return true;
    }

    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules["created_by_user_id"]);
        return $rules;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->proposal = Proposal::findOrFail($this->route("id"));
    }
}
