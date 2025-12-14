<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewRequest;
use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use App\Modules\Recruitment\Enums\RecruitmentApplicationStatus;
use App\Modules\Recruitment\Rules\RecruitmentApplicationExistsWithStatusRule;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

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
                    "bail",
                    "required",
                    "integer:strict",
                    new RecruitmentApplicationExistsWithStatusRule(RecruitmentApplicationStatus::INTERVIEW_PENDING),
                    function (string $attribute, mixed $value, Closure $fail) {
                        $hasInProgressInterview = Interview::where("recruitment_application_id", $value)
                            ->whereNotIn("status", [
                                InterviewStatus::COMPLETED->value,
                                InterviewStatus::CANCELLED->value,
                            ])
                            ->exists();

                        if ($hasInProgressInterview) {
                            $fail(
                                "An interview is already in progress for this application, please complete or cancel it.",
                            );
                        }
                    },
                ],
            ],
        ];
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
