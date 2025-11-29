<?php

namespace App\Modules\Proposal\Requests;

use App\Modules\Proposal\Abstracts\BaseProposalRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreProposalRequest extends BaseProposalRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            "position_id" => [
                "required",
                "integer",
                Rule::exists("position", "id")->where("department_id", Auth::user()->position->department->id),
            ],
        ]);
    }

    public function messages(): array
    {
        return [
            "position_id.exists" => "Position id must be in the same department as the current user",
        ];
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->merge([
            "created_by_user_id" => Auth::user()->id,
        ]);
    }
}
