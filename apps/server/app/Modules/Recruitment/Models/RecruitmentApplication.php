<?php

namespace App\Modules\Recruitment\Models;

use App\Abstracts\BaseModel;
use App\Modules\Candidate\Models\Candidate;
use App\Modules\Interview\Models\Interview;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecruitmentApplication extends BaseModel
{
    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }
}
