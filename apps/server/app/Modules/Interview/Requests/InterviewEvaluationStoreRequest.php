<?php

namespace App\Modules\Interview\Requests;

use App\Modules\Interview\Abstracts\BaseInterviewEvaluationRequest;
use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use App\Modules\Interview\Models\InterviewEvaluation;
use App\Modules\RatingScale\Models\RatingScalePoint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Validator;

class InterviewEvaluationStoreRequest extends BaseInterviewEvaluationRequest
{
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
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $interview = Interview::with("ratingScale")->find($this->input("interview_id"));

            if (!$interview) {
                $validator->errors()->add("interview_id", "Interview does not exist.");
            } elseif ($interview->status !== InterviewStatus::UNDER_EVALUATION) {
                $validator->errors()->add("interview_id", "Interview is not under evaluation.");
            }

            $userHasAlreadyEvaluated = InterviewEvaluation::where("interview_id", $this->input("interview_id"))
                ->where("created_by_user_id", Auth::user()->id)
                ->exists();

            if ($userHasAlreadyEvaluated) {
                $validator->errors()->add("interview_id", "You have already evaluated this interview.");
                return;
            }

            $ratingScalePoint = RatingScalePoint::find($this->input("rating_scale_point_id"));

            if (!$ratingScalePoint) {
                $validator->errors()->add("rating_scale_point_id", "Rating scale point does not exist.");
            } elseif ($ratingScalePoint->rating_scale_id !== $interview->ratingScale->id) {
                $validator
                    ->errors()
                    ->add(
                        "rating_scale_point_id",
                        "Rating scale point does not belong to this interview rating scale.",
                    );
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

        $this->merge([
            "created_by_user_id" => Auth::user()->id,
        ]);
    }
}
