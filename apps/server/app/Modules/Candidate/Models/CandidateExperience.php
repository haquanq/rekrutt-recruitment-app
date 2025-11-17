<?php

namespace App\Modules\Candidate\Models;

use App\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateExperience extends BaseModel
{
    protected $fillable = [
        "fromDate",
        "toDate",
        "employerName",
        "employerDescription",
        "positionTitle",
        "positionDuty",
        "note",
        "candidate_id",
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
