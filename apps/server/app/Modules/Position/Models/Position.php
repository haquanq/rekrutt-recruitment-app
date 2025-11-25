<?php

namespace App\Modules\Position\Models;

use App\Abstracts\BaseModel;
use App\Modules\Auth\Models\User;
use App\Modules\Department\Models\Department;
use App\Modules\Proposal\Models\Proposal;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends BaseModel
{
    protected $guarded = ["id", "created_at", "updated_at"];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }
}
