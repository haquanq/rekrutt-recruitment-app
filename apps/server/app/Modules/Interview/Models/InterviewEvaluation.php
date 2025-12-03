<?php

namespace App\Modules\Interview\Models;

use App\Abstracts\BaseModel;
use App\Modules\Auth\Models\User;
use App\Modules\RatingScale\Models\RatingScalePoint;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewEvaluation extends BaseModel
{
    protected $guarded = ["id", "created_at", "updated_at"];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ratingScalePoint(): BelongsTo
    {
        return $this->belongsTo(RatingScalePoint::class);
    }
}
