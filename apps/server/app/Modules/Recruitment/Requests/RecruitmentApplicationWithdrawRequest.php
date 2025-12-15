<?php

namespace App\Modules\Recruitment\Requests;

use App\Modules\Recruitment\Abstracts\BaseRecruitmentApplicationRequest;
use App\Modules\Recruitment\Enums\RecruitmentApplicationStatus;
use App\Modules\Recruitment\Models\RecruitmentApplication;
use App\Modules\Recruitment\Rules\RecruitmentApplicationStatusTransitionsFromRule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RecruitmentApplicationWithdrawRequest extends BaseRecruitmentApplicationRequest
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
                Rule::enum(RecruitmentApplicationStatus::class)->only([RecruitmentApplicationStatus::WITHDRAWN]),
                new RecruitmentApplicationStatusTransitionsFromRule(
                    $this->getQueriedRecruitmentApplicationOrFail()->status,
                ),
            ],

            /**
             * Withdrawn at timestamp === now
             * @ignoreParam
             */
            "withdrawn_at" => ["required", "date", "date_equals:now"],
            /**
             * Reason for withdrawal
             * @example Candidate does not want to continue
             */
            "withdrawn_reason" => ["required", "string", "max:300"],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("withdraw", RecruitmentApplication::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            "withdrawn_at" => Carbon::now(),
            "status" => RecruitmentApplicationStatus::WITHDRAWN->value,
        ]);
    }
}
