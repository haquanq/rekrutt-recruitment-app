<?php

namespace App\Modules\Interview\Models;

use App\Abstracts\BaseModel;
use App\Modules\Auth\Models\User;
use App\Modules\Recruitment\Models\RecruitmentApplication;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Interview extends BaseModel
{
    public function recruitmentApplication(): BelongsTo
    {
        return $this->belongsTo(RecruitmentApplication::class);
    }

    public function interviewType(): BelongsTo
    {
        return $this->belongsTo(InterviewMethod::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(InterviewEvaluation::class);
    }

    public function interviewers(): HasMany
    {
        return $this->hasMany(InterviewInterviewer::class);
    }
}
