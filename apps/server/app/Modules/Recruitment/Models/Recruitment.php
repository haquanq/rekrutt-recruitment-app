<?php

namespace App\Modules\Recruitment\Models;

use App\Abstracts\BaseModel;
use App\Modules\Auth\Models\User;
use App\Modules\Proposal\Models\Proposal;
use App\Modules\Recruitment\Enums\RecruitmentStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recruitment extends BaseModel
{
    protected $guarded = ["id", "created_at", "updated_at"];

    protected $casts = [
        "status" => RecruitmentStatus::class,
    ];

    public function isCreatedBy(User $user): bool
    {
        return $this->created_by_user_id === $user->id;
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by_user_id");
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "closed_by_user_id");
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(RecruitmentApplication::class);
    }
}
