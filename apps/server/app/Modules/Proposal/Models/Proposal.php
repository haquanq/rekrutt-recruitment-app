<?php

namespace App\Modules\Proposal\Models;

use App\Abstracts\BaseModel;
use App\Modules\Auth\Models\User;
use App\Modules\ContractType\Models\ContractType;
use App\Modules\EducationLevel\Models\EducationLevel;
use App\Modules\ExperienceLevel\Models\ExperienceLevel;
use App\Modules\Position\Models\Position;
use App\Modules\Recruitment\Models\Recruitment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proposal extends BaseModel
{
    protected $guarded = ["id", "created_at", "updated_at"];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by_user_id");
    }

    public function reviewer(): BelongsTo
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

    public function recruitment(): HasOne
    {
        return $this->hasOne(Recruitment::class);
    }
}
