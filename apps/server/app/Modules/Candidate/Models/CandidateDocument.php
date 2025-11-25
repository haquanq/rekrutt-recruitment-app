<?php

namespace App\Modules\Candidate\Models;

use App\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateDocument extends BaseModel
{
    protected $guarded = ["id", "created_at", "updated_at"];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
