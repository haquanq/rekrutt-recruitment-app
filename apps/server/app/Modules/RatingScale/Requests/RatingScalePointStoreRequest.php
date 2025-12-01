<?php

namespace App\Modules\RatingScale\Requests;

use App\Modules\RatingScale\Abstracts\BaseRatingScalePointRequest;

class RatingScalePointStoreRequest extends BaseRatingScalePointRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), []);
    }
}
