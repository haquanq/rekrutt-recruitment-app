<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewEvaluationRequest;
use App\Modules\Interview\Models\InterviewEvaluation;
use App\Modules\RatingScale\Rules\RatingScalePointBelongsToScaleRule;
use Illuminate\Support\Facades\Gate;

class InterviewEvaluationUpdateRequest extends BaseInterviewEvaluationRequest
{
    public InterviewEvaluation $interviewEvaluation;

    public function rules(): array
    {
        return [
            ...parent::rules(),
            ...[
                /**
                 * Id of RatingScalePoint (belongs to RatingScale selected for this Interview)
                 * @example 1
                 */
                "rating_scale_point_id" => [
                    "required",
                    "integer:strict",
                    new RatingScalePointBelongsToScaleRule($this->interviewEvaluation->interview->ratingScale),
                ],
            ],
        ];
    }

    public function authorize(): bool
    {
        Gate::authorize("update", $this->interviewEvaluation);
        return true;
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->interviewEvaluation = InterviewEvaluation::with("interview.ratingScale")->findOrFail($this->route("id"));
    }
}
