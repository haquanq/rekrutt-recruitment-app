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

class CandidateBlacklistRequest extends BaseCandidateRequest
{
    public function rules(): array
    {
        return [
            /**
             * Status === BLACKLISTED
             * @ignoreParam
             */
            "status" => [
                "bail",
                "required",
                Rule::enum(CandidateStatus::class)->only(CandidateStatus::BLACKLISTED),
                new CandidateStatusTransitionsFromRule($this->getQueriedCandidateOrFail()->status),
            ],
            /**
             * Timestamp
             * @ignoreParam
             */
            "blacklisted_at" => ["required", "date", "after_or_equal:now"],
            /**
             * Reason for blacklisting
             * @example Candidate provided false information
             */
            "blacklisted_reason" => ["required", "string", "max:500"],
            /**
             * Blacklisted by user
             * @ignoreParam
             */
            "blacklisted_by_user_id" => ["required", "integer"],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("blacklist", Candidate::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            "blacklisted_by_user_id" => Auth::user()->id,
            "blacklisted_at" => Carbon::now(),
            "status" => CandidateStatus::BLACKLISTED->value,
        ]);
    }
}
