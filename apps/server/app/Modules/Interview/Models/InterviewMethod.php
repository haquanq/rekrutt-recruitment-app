<?php

namespace App\Modules\Interview\Models;

use App\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InterviewMethod extends BaseModel
{
    protected $guarded = ["id", "created_at", "updated_at"];

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }
}
