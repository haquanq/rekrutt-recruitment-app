<?php

namespace App\Modules\RatingScale\Requests;

use App\Modules\RatingScale\Abstracts\BaseRatingScaleRequest;
use App\Modules\RatingScale\Models\RatingScale;
use Illuminate\Support\Facades\Gate;

class RatingScaleStoreRequest extends BaseRatingScaleRequest
{
    public function authorize(): bool
    {
        Gate::authorize("create", RatingScale::class);
        return true;
    }
}
