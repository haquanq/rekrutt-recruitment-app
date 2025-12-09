<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewEvaluationRequest;
use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use App\Modules\Interview\Models\InterviewEvaluation;
use App\Modules\Interview\Rules\InterviewExistsWithStatusRule;
use App\Modules\RatingScale\Rules\RatingScalePointBelongsToScaleRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Validator;

class InterviewEvaluationStoreRequest extends BaseInterviewEvaluationRequest
{
    public ?Interview $interview = null;

    public function rules(): array
    {
        return [
            ...parent::rules(),
            ...[
                /**
                 * Created by current User
                 * @ignoreParam
                 */
                "created_by_user_id" => ["required", "integer:strict"],
                /**
                 * Id of Interview
                 * @example 1
                 */
                "interview_id" => ["required", "integer:strict"],
                /**
                 * Id of RatingScalePoint (belongs to RatingScale selected for this Interview)
                 * @example 1
                 */
                "rating_scale_point_id" => ["required", "integer:strict"],
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->addRules([
            "interview_id" => [
                InterviewExistsWithStatusRule::create(InterviewStatus::UNDER_EVALUATION)->withInterview(
                    $this->interview,
                ),
            ],
        ]);

        if (!$this->interview) {
            return;
        }

        $validator->addRules([
            "rating_scale_point_id" => [new RatingScalePointBelongsToScaleRule($this->interview->ratingScale)],
        ]);

        $validator->after(function (Validator $validator) {
            $userHasAlreadyEvaluated = InterviewEvaluation::where("interview_id", $this->interview->id)
                ->where("created_by_user_id", Auth::user()->id)
                ->exists();

            if ($userHasAlreadyEvaluated) {
                $validator->errors()->add("interview_id", "You have already evaluated this interview.");
            }
        });
    }

    public function authorize(): bool
    {
        Gate::authorize("create", [InterviewEvaluation::class, $this->interview]);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->interview = Interview::with("ratingScale")->find($this->input("interview_id"));

        $this->merge([
            "created_by_user_id" => Auth::user()->id,
        ]);
    }
}
