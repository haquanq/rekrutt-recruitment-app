<?php

namespace App\Modules\Interview\Models;

use App\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewInterviewer extends BaseModel
{
    public function interviews(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }
}
