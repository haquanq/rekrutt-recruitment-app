<?php

namespace App\Modules\Candidate\Requests;

use App\Modules\Candidate\Abstracts\BaseCandidateRequest;
use App\Modules\Candidate\Enums\CandidateStatus;
use App\Modules\Candidate\Models\Candidate;
use App\Modules\Candidate\Rules\CandidateStatusTransitionsFromRule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CandidateReactivateRequest extends BaseCandidateRequest
{
    public function rules(): array
    {
        return [
            /**
             * Status === READY
             * @ignoreParam
             */
            "status" => [
                "bail",
                "required",
                Rule::enum(CandidateStatus::class)->only(CandidateStatus::READY),
                new CandidateStatusTransitionsFromRule($this->getQueriedCandidateOrFail()->status),
            ],
            /**
             * Timestamp
             * @ignoreParam
             */
            "reactivated_at" => ["required", "date", "after_or_equal:now"],
            /**
             * Reason for reactivation
             * @example Candidate allowed to reapply
             */
            "reactivated_reason" => ["required", "string", "max:500"],
            /**
             * reactivated by user
             * @ignoreParam
             */
            "reactivated_by_user_id" => ["required", "integer"],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("reactivate", Candidate::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            "status" => CandidateStatus::READY->value,
            "reactivated_by_user_id" => Auth::user()->id,
            "reactivated_at" => Carbon::now(),
        ]);
    }
}
