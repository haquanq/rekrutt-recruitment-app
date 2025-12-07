<?php

namespace App\Modules\Interview\Models;

use App\Abstracts\BaseModel;
use App\Modules\Auth\Models\User;
use App\Modules\RatingScale\Resources\RatingScaleResource;
use App\Modules\Recruitment\Models\RecruitmentApplication;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Interview extends BaseModel
{
    protected $guarded = ["id", "created_at", "updated_at"];

    public function isCreatedBy(User $user): bool
    {
        return $this->created_by_user_id === $user->id;
    }

    public function hasParticipant(User $user): bool
    {
        return $this->participants->pluck("id")->has($user->id);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(RecruitmentApplication::class);
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(InterviewMethod::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(InterviewEvaluation::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(InterviewParticipant::class);
    }

    public function ratingScale(): BelongsTo
    {
        return $this->belongsTo(RatingScaleResource::class);
    }
}
