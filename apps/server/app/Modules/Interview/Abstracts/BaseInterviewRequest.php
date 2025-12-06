<?php

namespace App\Modules\Interview\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\RatingScale\Rules\RatingScaleExistsAndIsActiveRule;

abstract class BaseInterviewRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            /**
             * Title
             * @example Screening Interview (Phone/Video)
             */
            "title" => ["required", "string", "max:100"],
            /**
             * Description
             * @example Prepare for an initial assessment to showcase your skills and potential as a candidate for a Software Engineering role at CodeCraft
             */
            "description" => ["required", "string", "max:500"],
            /**
             * Location where interview will be held
             * @example Online
             */
            "location" => ["required", "string", "max:300"],
            /**
             * Start time
             * @example 2030-12-01 12:00
             */
            "started_at" => ["required", "date", "after:today"],
            /**
             * End time
             * @example 2030-12-01 13:00
             */
            "ended_at" => ["required", "date", "after:started_at"],
            /**
             * Id of RatingScale
             * @example 1
             */
            "rating_scale_id" => ["required", "integer:strict", new RatingScaleExistsAndIsActiveRule()],
            /**
             * Id of Interview
             * @example 1
             */
            "interview_method_id" => ["required", "integer:strict", "exists:interview_method,id"],
            /**
             * Id of InterviewMethod
             * @example 1
             */
        ];
    }
}
