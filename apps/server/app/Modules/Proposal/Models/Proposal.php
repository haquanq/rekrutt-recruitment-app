<?php

namespace App\Modules\Proposal\Models;

use App\Abstracts\BaseModel;
use App\Modules\Auth\Models\User;
use App\Modules\ContractType\Models\ContractType;
use App\Modules\EducationLevel\Models\EducationLevel;
use App\Modules\ExperienceLevel\Models\ExperienceLevel;
use App\Modules\Position\Models\Position;
use App\Modules\Proposal\Enums\ProposalStatus;
use App\Modules\Recruitment\Models\Recruitment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proposal extends BaseModel
{
    protected $guarded = ["id", "created_at", "updated_at"];

    protected $casts = [
        "status" => ProposalStatus::class,
    ];

    public function isCreatedBy(User $user): bool
    {
        return $this->created_by_user_id === $user->id;
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by_user_id");
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "reviewed_by_user_id");
    }

    public function contractType(): BelongsTo
    {
        return $this->belongsTo(ContractType::class);
    }

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function experienceLevel(): BelongsTo
    {
        return $this->belongsTo(ExperienceLevel::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ProposalDocument::class);
    }

    public function recruitments(): HasMany
    {
        return $this->hasMany(Recruitment::class);
    }
}
