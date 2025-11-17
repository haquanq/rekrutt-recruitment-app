<?php

namespace App\Modules\ContractType\Models;

use App\Abstracts\BaseModel;
use App\Modules\Proposal\Models\Proposal;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractType extends BaseModel
{
    protected $fillable = ["name", "description"];

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }
}
