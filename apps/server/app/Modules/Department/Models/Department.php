<?php

namespace App\Modules\Department\Models;

use App\Abstracts\BaseModel;
use App\Modules\Position\Models\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends BaseModel
{
    use HasFactory;

    protected $fillable = ["name", "description"];
    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }
}
