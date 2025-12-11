<?php

namespace App\Modules\RatingScale\Requests;

use App\Modules\RatingScale\Abstracts\BaseRatingScaleRequest;
use App\Modules\RatingScale\Models\RatingScale;
use Illuminate\Support\Facades\Gate;

class RatingScaleDestroyRequest extends BaseRatingScaleRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", RatingScale::class);
        return true;
    }
}
