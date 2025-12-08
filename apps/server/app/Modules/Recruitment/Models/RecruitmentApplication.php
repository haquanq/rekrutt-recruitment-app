<?php

namespace App\Modules\Recruitment\Models;

use App\Abstracts\BaseModel;
use App\Modules\Auth\Models\User;
use App\Modules\Candidate\Models\Candidate;
use App\Modules\Interview\Models\Interview;
use App\Modules\Recruitment\Enums\RecruitmentApplicationPriority;
use App\Modules\Recruitment\Enums\RecruitmentApplicationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecruitmentApplication extends BaseModel
{
    protected $guarded = ["id", "created_at", "updated_at"];

    protected $casts = [
        "status" => RecruitmentApplicationStatus::class,
        "priority" => RecruitmentApplicationPriority::class,
    ];

    public function scopeCompleted(Builder $query): void
    {
        $query->whereIn("status", [
            RecruitmentApplicationStatus::WITHDRAWN->value,
            RecruitmentApplicationStatus::DISCARDED->value,
            RecruitmentApplicationStatus::OFFER_ACCEPTED->value,
            RecruitmentApplicationStatus::OFFER_REJECTED->value,
        ]);
    }

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function discardedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "discarded_by_user_id", "id");
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }
}
