<?php

namespace App\Modules\Interview\Policies;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;
use App\Modules\Interview\Models\Interview;
use App\Modules\Interview\Models\InterviewParticipant;
use Illuminate\Auth\Access\Response;

class InterviewParticipantPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user): bool
    {
        return true;
    }

    public function create(User $user, Interview $interview): Response
    {
        if ($user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            Response::deny("You are not allowed to create new interview participant");
        } elseif (!$interview->isCreatedBy($user)) {
            Response::deny("You are not the creator of this interview");
        }

        return Response::allow();
    }

    public function update(User $user, InterviewParticipant $interviewParticipant): Response
    {
        if ($user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            Response::deny("You are not allowed to update any interview participant");
        } elseif (!$interviewParticipant->interview->isCreatedBy($user)) {
            Response::deny("You are not the creator of this interview");
        }

        return Response::allow();
    }

    public function delete(User $user, InterviewParticipant $interviewParticipant): Response
    {
        if ($user->hasRole(UserRole::HIRING_MANAGER, UserRole::RECRUITER)) {
            Response::deny("You are not allowed to delete any interview participant");
        } elseif (!$interviewParticipant->interview->isCreatedBy($user)) {
            Response::deny("You are not the creator of this interview");
        }

        return Response::allow();
    }
}
