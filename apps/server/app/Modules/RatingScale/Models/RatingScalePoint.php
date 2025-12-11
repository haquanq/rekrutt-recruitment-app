<?php

namespace App\Modules\RatingScale\Models;

use App\Abstracts\BaseModel;
use App\Modules\Interview\Models\InterviewEvaluation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RatingScalePoint extends BaseModel
{
    protected $guarded = ["id", "created_at", "updated_at"];

    public function ratingScale(): BelongsTo
    {
        return $this->belongsTo(RatingScale::class);
    }

    protected static function booted()
    {
        static::addGlobalScope("orderByRank", function ($query) {
            $query->orderBy("rank");
        });
    }
}
