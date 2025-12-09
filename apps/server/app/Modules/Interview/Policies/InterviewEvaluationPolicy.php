<?php

namespace App\Modules\Interview\Policies;

use App\Modules\Auth\Models\User;
use App\Modules\Interview\Models\Interview;
use App\Modules\Interview\Models\InterviewEvaluation;
use Illuminate\Auth\Access\Response;

class InterviewEvaluationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user): bool
    {
        return true;
    }

    public function create(User $user, ?Interview $interview): Response
    {
        if ($interview && !$interview->hasParticipant($user)) {
            return Response::deny("You are not participating in this interview");
        }

        return Response::allow();
    }

    public function update(User $user, InterviewEvaluation $interviewEvaluation): Response
    {
        if (!$interviewEvaluation->isCreatedBy($user)) {
            return Response::deny("You are not the creator of this interview evaluation");
        }

        return Response::allow();
    }

    public function delete(User $user, InterviewEvaluation $interviewEvaluation): Response
    {
        if (!$interviewEvaluation->isCreatedBy($user)) {
            return Response::deny("You are not the creator of this interview evaluation");
        }

        return Response::allow();
    }
}
