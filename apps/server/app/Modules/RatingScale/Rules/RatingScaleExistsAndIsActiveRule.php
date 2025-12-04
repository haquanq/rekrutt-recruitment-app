<?php

namespace App\Modules\RatingScale\Rules;

use App\Modules\RatingScale\Models\RatingScale;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RatingScaleExistsAndIsActiveRule implements ValidationRule
{
    public function validate(string $attribute, mixed $id, Closure $fail): void
    {
        $ratingScare = RatingScale::find($id);

        if (!$ratingScare) {
            $fail("Rating scale does not exist");
            return;
        }

        if (!$ratingScare->is_active) {
            $fail("Rating scale is not active");
            return;
        }
    }
}
