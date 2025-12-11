<?php

namespace App\Modules\Recruitment\Requests;

use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use App\Modules\Recruitment\Abstracts\BaseRecruitmentApplicationRequest;
use App\Modules\Recruitment\Enums\RecruitmentApplicationStatus;
use App\Modules\Recruitment\Models\RecruitmentApplication;
use App\Modules\Recruitment\Rules\RecruitmentApplicationStatusTransitionsFromRule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class RecruitmentApplicationUpdateInterviewStatusRequest extends BaseRecruitmentApplicationRequest
{
    public function rules(): array
    {
        return [
            /**
             * Status
             * @example INTERVIEW_PENDING
             */
            "status" => [
                "bail",
                "required",
                Rule::enum(RecruitmentApplicationStatus::class)->only([
                    RecruitmentApplicationStatus::INTERVIEW_PENDING,
                    RecruitmentApplicationStatus::INTERVIEW_COMPLETED,
                ]),
                new RecruitmentApplicationStatusTransitionsFromRule($this->getRecruitmentApplicationOrFail()->status),
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            if ($this->input("status") === RecruitmentApplicationStatus::INTERVIEW_COMPLETED->value) {
                $hasInProgressInterview = Interview::where(
                    "recruitment_application_id",
                    $this->recruitmentApplication->id,
                )
                    ->whereNotIn("status", [InterviewStatus::COMPLETED->value, InterviewStatus::CANCELLED->value])
                    ->exists();

                if ($hasInProgressInterview) {
                    $validator
                        ->errors()
                        ->add(
                            "interviews",
                            "All interviews must be completed. Please complete or cancel all interviews.",
                        );
                }
            }
        });
    }

    public function authorize(): bool
    {
        Gate::authorize("updateInterviewStatus", RecruitmentApplication::class);
        return true;
    }
}
