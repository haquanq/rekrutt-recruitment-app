<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewEvaluationRequest;
use App\Modules\RatingScale\Rules\RatingScalePointBelongsToScaleRule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Validator;

class InterviewEvaluationUpdateRequest extends BaseInterviewEvaluationRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            ...[
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
            "rating_scale_point_id" => [
                new RatingScalePointBelongsToScaleRule(
                    $this->getQueriedInterviewEvaluationOrFail()->interview->ratingScale,
                ),
            ],
        ]);
    }

    public function authorize(): bool
    {
        Gate::authorize("update", $this->getQueriedInterviewEvaluationOrFail());
        return true;
    }
}
