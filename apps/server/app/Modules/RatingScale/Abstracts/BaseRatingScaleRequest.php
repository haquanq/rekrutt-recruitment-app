<?php

namespace App\Modules\RatingScale\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\RatingScale\Models\RatingScale;
use Illuminate\Validation\Rule;

abstract class BaseRatingScaleRequest extends BaseFormRequest
{
    protected ?RatingScale $ratingScale = null;

    public function getQueriedRatingScaleOrFail(string $param = "id"): RatingScale
    {
        if ($this->ratingScale === null) {
            $this->ratingScale = RatingScale::findOrFail($this->route($param));
        }

        return $this->ratingScale;
    }

    public function rules(): array
    {
        return [
            /**
             * Name
             * @example 10-Point Numerical Scale
             */
            "name" => [
                "bail",
                "required",
                "string",
                "max:100",
                Rule::unique("rating_scales", "name")->ignore($this->route("id")),
            ],
            /**
             * Description
             * @example The 10-Point Numerical Scale is a rating scale that ranges from 0 to 10, with 0 being the lowest rating and 10 being the highest rating.
             */
            "description" => ["nullable", "string", "max:500"],
            /**
             * Whether the rating scale is active
             * @example true
             */
            "is_active" => ["required", "boolean"],
        ];
    }
}
