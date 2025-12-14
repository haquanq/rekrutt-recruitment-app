<?php

namespace App\Modules\Interview\Models;

use App\Abstracts\BaseModel;
use App\Modules\Auth\Models\User;
use App\Modules\RatingScale\Models\RatingScalePoint;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewEvaluation extends BaseModel
{
    protected $guarded = ["id", "created_at", "updated_at"];

    protected $with = ["point"];

    public function isCreatedBy(User $user): bool
    {
        return $this->created_by_user_id === $user->id;
    }

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by_user_id", "id");
    }

    public function point(): BelongsTo
    {
        return $this->belongsTo(RatingScalePoint::class, "rating_scale_point_id", "id");
    }
}
