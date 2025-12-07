<?php

namespace App\Modules\RatingScale\Rules;

use App\Modules\RatingScale\Models\RatingScale;
use App\Modules\RatingScale\Models\RatingScalePoint;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RatingScalePointBelongsToScaleRule implements ValidationRule
{
    public function __construct(protected RatingScale $ratingScale) {}

    public function validate(string $attribute, mixed $id, Closure $fail): void
    {
        $ratingScalePoint = RatingScalePoint::with("ratingScale")->find($id);

        if (!$ratingScalePoint) {
            $fail("Rating scale point does not exist.");
            return;
        }

        if ($ratingScalePoint->ratingScale->id !== $this->ratingScale->id) {
            $fail("Rating scale point does not belong to this rating scale.");
            return;
        }
    }
}
