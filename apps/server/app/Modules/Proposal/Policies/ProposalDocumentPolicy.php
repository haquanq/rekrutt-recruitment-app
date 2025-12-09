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

    public function create(User $user, ?Proposal $proposal): Response
    {
        if (!$user->hasRole(UserRole::MANAGER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to create new proposal document.");
        } elseif ($proposal && !$proposal->isCreatedBy($user)) {
            return Response::deny("You are not the creator of the selected proposal.");
        }
        return Response::allow();
    }

    public function update(User $user, ProposalDocument $proposalDocument): Response
    {
        if (!$user->hasRole(UserRole::MANAGER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to update any proposal document.");
        } elseif (!$proposalDocument->proposal->isCreatedBy($user)) {
            return Response::deny("You are not the creator of the proposal of the selected document.");
        }

        return Response::allow();
    }

    public function delete(User $user, ProposalDocument $proposalDocument): Response
    {
        if (!$user->hasRole(UserRole::MANAGER, UserRole::HIRING_MANAGER)) {
            return Response::deny("You are not allowed to delete any proposal document.");
        } elseif (!$proposalDocument->proposal->isCreatedBy($user)) {
            return Response::deny("You are not the creator of the proposal of the selected document.");
        }

        return Response::allow();
    }
}
