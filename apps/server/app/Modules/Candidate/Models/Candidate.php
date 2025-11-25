<?php

namespace App\Modules\Candidate\Models;

use App\Abstracts\BaseModel;
use App\Modules\HiringSource\Models\HiringSource;
use App\Modules\Recruitment\Models\RecruitmentApplication;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends BaseModel
{
    protected $guarded = ["id", "created_at", "updated_at"];

    public function source(): BelongsTo
    {
        return $this->belongsTo(HiringSource::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(CandidateExperience::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CandidateDocument::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(RecruitmentApplication::class);
    }
}
