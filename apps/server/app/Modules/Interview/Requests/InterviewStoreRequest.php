<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewRequest;
use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use App\Modules\Recruitment\Enums\RecruitmentApplicationStatus;
use App\Modules\Recruitment\Models\RecruitmentApplication;
use App\Modules\Recruitment\Rules\RecruitmentApplicationExistsWithStatusRule;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class InterviewStoreRequest extends BaseInterviewRequest
{
    public ?RecruitmentApplication $recruitmentApplication = null;

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
                "recruitment_application_id" => ["bail", "required", "integer:strict"],
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->addRules([
            "recruitment_application_id" => [
                RecruitmentApplicationExistsWithStatusRule::create(
                    RecruitmentApplicationStatus::INTERVIEW_PENDING,
                )->withRecruitmentApplication($this->recruitmentApplication),
                function (string $attribute, mixed $value, Closure $fail) {
                    $hasInProgressInterview = Interview::where("recruitment_application_id", $value)
                        ->whereNotIn("status", [InterviewStatus::COMPLETED->value, InterviewStatus::CANCELLED->value])
                        ->exists();
                    if ($hasInProgressInterview) {
                        $fail(
                            "An interview is already in progress for this application, please complete or cancel it.",
                        );
                    }
                },
            ],
        ]);

        if (!$this->recruitmentApplication) {
            return;
        }
    }

    public function authorize(): bool
    {
        Gate::authorize("create", Interview::class);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->recruitmentApplication = RecruitmentApplication::with("interviews")->find(
            $this->input("recruitment_application_id"),
        );

        $this->merge([
            "created_by_user_id" => Auth::user()->id,
            "status" => InterviewStatus::DRAFT->value,
        ]);
    }
}
