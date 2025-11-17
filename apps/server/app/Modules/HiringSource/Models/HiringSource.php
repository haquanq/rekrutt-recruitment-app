<?php

namespace App\Modules\HiringSource\Models;

use App\Abstracts\BaseModel;
use App\Modules\Candidate\Models\Candidate;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HiringSource extends BaseModel
{
    protected $fillable = ["name", "description", "site_url"];

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }
}
