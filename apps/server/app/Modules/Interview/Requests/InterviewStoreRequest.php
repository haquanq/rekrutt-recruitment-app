<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewRequest;
use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use App\Modules\Recruitment\Enums\RecruitmentApplicationStatus;
use App\Modules\Recruitment\Models\RecruitmentApplication;
use App\Modules\Recruitment\Rules\RecruitmentApplicationExistsWithStatusRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class InterviewStoreRequest extends BaseInterviewRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            ...[
                /**
                 * Status === DRAFT
                 * @ignoreParam
                 */
                "status" => ["required", Rule::enum(InterviewStatus::class)->only(InterviewStatus::DRAFT)],
                /**
                 * Created by user (generated automatically)
                 * @ignoreParam
                 */
                "created_by_user_id" => ["required", "integer:strict"],
                /**
                 * Id of RecruitmentApplication
                 * @example 1
                 */
                "recruitment_application_id" => [
                    "required",
                    "integer:strict",
                    new RecruitmentApplicationExistsWithStatusRule(RecruitmentApplicationStatus::INTERVIEW_PLANNING),
                ],
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $recruitmentApplication = RecruitmentApplication::withCount("interviews")->findOrFail(
                $this->input("recruitment_application_id"),
            );

            if ($recruitmentApplication->interviews_count >= $recruitmentApplication->number_of_interviews) {
                $validator
                    ->errors()
                    ->add(
                        "recruitment_application_id",
                        "Recruitment application has reached the maximum number of interviews.",
                    );
            }
        });
    }

    public function authorize(): bool
    {
        Gate::authorize("create", Interview::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            "created_by_user_id" => Auth::user()->id,
            "status" => InterviewStatus::DRAFT->value,
        ]);
    }
}
