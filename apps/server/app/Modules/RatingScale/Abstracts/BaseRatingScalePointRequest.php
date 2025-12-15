<?php

namespace App\Modules\RatingScale\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\RatingScale\Models\RatingScalePoint;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

abstract class BaseRatingScalePointRequest extends BaseFormRequest
{
    protected ?RatingScalePoint $ratingScalePoint = null;

    public function getQueriedRatingScalePointOrFail(string $param = "id"): RatingScalePoint
    {
        if ($this->ratingScalePoint === null) {
            $this->ratingScalePoint = RatingScalePoint::findOrFail($this->route($param));
        }

        return $this->ratingScalePoint;
    }

    public function rules(): array
    {
        return [
            /**
             * Rank/order
             * @example 1
             */
            "rank" => ["required", "integer"],
            /**
             * Label
             * @example 9 - Pretty good
             */
            "label" => [
                "bail",
                "required",
                "string",
                "max:100",
                Rule::unique("rating_scale_point")->where(function (Builder $query) {
                    return $query->where("rating_scale_id", $this->input("rating_scale_id"));
                }),
            ],
            /**
             * Description
             * @example Candidate scored 9 out of 10
             */
            "definition" => ["required", "string", "max:300"],
            /**
             * Rating scale Id of which this rating scale point belongs
             * @example 1
             */
            "rating_scale_id" => ["bail", "required", "integer:strict", Rule::exists("rating_scale", "id")],
        ];
    }

    public function messages(): array
    {
        return [
            "label.unique" => "A rating scale point with this label already exists for this rating scale.",
        ];
    }
}
