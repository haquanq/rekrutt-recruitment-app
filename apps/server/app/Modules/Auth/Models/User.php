<?php

namespace App\Modules\Auth\Models;

use App\Modules\Interview\Models\Interview;
use App\Modules\Interview\Models\InterviewEvaluation;
use App\Modules\Interview\Models\InterviewInterviewer;
use App\Modules\Position\Models\Position;
use App\Modules\Proposal\Models\Proposal;
use App\Modules\Recruitment\Models\Recruitment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = "user";

    protected $casts = [
        "password" => "hashed",
    ];

    protected $guarded = [
        "id",
        "created_at",
        "updated_at",
        "suspension_started_at",
        "suspension_ended_at",
        "suspension_note",
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function createdProposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function createdRecruitments(): HasMany
    {
        return $this->hasMany(Recruitment::class);
    }

    public function createdInterviews(): HasMany
    {
        return $this->hasMany(Interview::class)->where("");
    }

    public function joinedInterviews(): BelongsToMany
    {
        return $this->belongsToMany(InterviewInterviewer::class);
    }

    public function evaluatedInterviews(): HasMany
    {
        return $this->hasMany(InterviewEvaluation::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
