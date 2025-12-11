<?php

namespace App\Modules\Candidate\Requests;

use App\Modules\Candidate\Abstracts\BaseCandidateRequest;
use App\Modules\Candidate\Enums\CandidateStatus;
use App\Modules\Candidate\Models\Candidate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CandidateStoreRequest extends BaseCandidateRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            ...[
                /**
                 * Initial status (generated automatically)
                 * @ignoreParam
                 */
                "status" => ["required", Rule::enum(CandidateStatus::class)->only(CandidateStatus::READY)],
            ],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("create", Candidate::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            "status" => CandidateStatus::READY->value,
        ]);
    }
}
