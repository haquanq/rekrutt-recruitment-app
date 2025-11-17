<?php

namespace App\Modules\Candidate\Models;

use App\Abstracts\BaseModel;
use App\Modules\HiringSource\Models\HiringSource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends BaseModel
{
    protected $fillable = ["firstName", "lastName", "dateOfBirth", "address", "email", "phoneNumber", "hiringSourceId"];

    public function experiences(): HasMany
    {
        return $this->hasMany(CandidateExperience::class);
    }

    public function hiringSource(): BelongsTo
    {
        return $this->belongsTo(HiringSource::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CandidateDocument::class);
    }
}
