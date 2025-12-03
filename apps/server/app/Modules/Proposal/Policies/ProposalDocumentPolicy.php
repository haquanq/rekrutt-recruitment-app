<?php

namespace App\Modules\Proposal\Policies;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Models\User;
use App\Modules\Proposal\Models\Proposal;
use App\Modules\Proposal\Models\ProposalDocument;
use Illuminate\Auth\Access\Response;

class ProposalDocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user): bool
    {
        return true;
    }

    public function create(User $user, Proposal $proposal): Response
    {
        if (!$user->hasRole(UserRole::MANAGER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to create proposal document.");
        } elseif ($user->id !== $proposal->created_by_user_id) {
            return Response::deny("You are not the author of the selected proposal.");
        }
        return Response::allow();
    }

    public function update(User $user, ProposalDocument $proposalDocument): Response
    {
        if (!$user->hasRole(UserRole::MANAGER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to update proposal document.");
        } elseif ($user->id !== $proposalDocument->proposal->created_by_user_id) {
            return Response::deny("You are not the author of the proposal of the selected document.");
        }

        return Response::allow();
    }

    public function delete(User $user, ProposalDocument $proposalDocument): Response
    {
        if (!$user->hasRole(UserRole::MANAGER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to delete proposal document.");
        } elseif ($user->id !== $proposalDocument->proposal->created_by_user_id) {
            return Response::deny("You are not the author of the proposal of the selected document.");
        }

        return Response::allow();
    }
}
