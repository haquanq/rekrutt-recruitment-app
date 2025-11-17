<?php

namespace App\Modules\RatingScale\Models;

use App\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RatingScale extends BaseModel
{
    public function points(): HasMany
    {
        return $this->hasMany(RatingScalePoint::class);
    }
}
