<?php

namespace App\Modules\RatingScale\Models;

use App\Abstracts\BaseModel;
use App\Modules\Interview\Models\InterviewEvaluation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RatingScalePoint extends BaseModel
{
    public function ratingScale(): BelongsTo
    {
        return $this->belongsTo(RatingScale::class);
    }

    public function interviewEvaluations(): HasMany
    {
        return $this->hasMany(InterviewEvaluation::class);
    }
}
