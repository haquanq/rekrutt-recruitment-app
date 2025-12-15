<?php

namespace App\Modules\Recruitment\Requests;

use App\Modules\Recruitment\Abstracts\BaseRecruitmentApplicationRequest;
use App\Modules\Recruitment\Enums\RecruitmentApplicationStatus;
use App\Modules\Recruitment\Models\RecruitmentApplication;
use App\Modules\Recruitment\Rules\RecruitmentApplicationStatusTransitionsFromRule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RecruitmentApplicationDiscardRequest extends BaseRecruitmentApplicationRequest
{
    public function rules(): array
    {
        return [
            /**
             * Status === DISCARDED
             * @ignoreParam
             */
            "status" => [
                "bail",
                "required",
                Rule::enum(RecruitmentApplicationStatus::class)->only([RecruitmentApplicationStatus::DISCARDED]),
                new RecruitmentApplicationStatusTransitionsFromRule(
                    $this->getQueriedRecruitmentApplicationOrFail()->status,
                ),
            ],
            /**
             * Discarded at timestamp === now
             * @ignoreParam
             */
            "discarded_at" => ["required", "date", "date_equals:now"],
            /**
             * Reason for discarding
             * @example Candidate did not pass the interview
             */
            "discarded_reason" => ["required", "string", "max:300"],
            /**
             * Discarded by User
             * @ignoreParam
             */
            "discarded_by_user_id" => ["required", "integer:strict"],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("discard", RecruitmentApplication::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            "discarded_by_user_id" => Auth::user()->id,
            "discarded_at" => Carbon::now(),
            "status" => RecruitmentApplicationStatus::DISCARDED->value,
        ]);
    }
}
