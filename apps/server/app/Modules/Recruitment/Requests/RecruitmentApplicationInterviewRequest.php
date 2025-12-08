<?php

namespace App\Modules\Recruitment\Requests;

use App\Modules\Recruitment\Abstracts\BaseRecruitmentApplicationRequest;
use App\Modules\Recruitment\Enums\RecruitmentApplicationStatus;
use App\Modules\Recruitment\Models\RecruitmentApplication;
use App\Modules\Recruitment\Rules\RecruitmentApplicationStatusTransitionsFromRule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RecruitmentApplicationInterviewRequest extends BaseRecruitmentApplicationRequest
{
    public RecruitmentApplication $recruitmentApplication;

    public function rules(): array
    {
        return [
            /**
             * Status
             * @example INTERVIEW_PLANNING
             */
            "status" => [
                "required",
                Rule::enum(RecruitmentApplicationStatus::class)->only([
                    RecruitmentApplicationStatus::INTERVIEW_PLANNING,
                    RecruitmentApplicationStatus::INTERVIEW_SCHEDULED,
                ]),
                new RecruitmentApplicationStatusTransitionsFromRule($this->recruitmentApplication->status),
            ],
            /**
             * Number of rounds (include when status is INTERVIEW_PLANNING)
             * @example 4
             * @default 1
             */
            "number_of_rounds" => [
                Rule::excludeIf($this->input("status") !== RecruitmentApplicationStatus::INTERVIEW_PLANNING->value),
                "required",
                "integer",
                "min:1",
                "max:10",
            ],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("updateInterviewStatus", RecruitmentApplication::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->recruitmentApplication = RecruitmentApplication::findOrFail($this->route("id"));
    }
}
