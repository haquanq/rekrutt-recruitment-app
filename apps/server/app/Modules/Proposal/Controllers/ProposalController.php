<?php

namespace App\Modules\Proposal\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Proposal\Enums\ProposalStatus;
use App\Modules\Proposal\Requests\ProposalApproveRequest;
use App\Modules\Proposal\Requests\ProposalDestroyRequest;
use App\Modules\Proposal\Requests\ProposalRejectRequest;
use App\Modules\Proposal\Requests\ProposalStoreRequest;
use App\Modules\Proposal\Requests\ProposalSubmitRequest;
use App\Modules\Proposal\Requests\ProposalUpdateRequest;
use App\Modules\Proposal\Resources\ProposalResource;
use App\Modules\Proposal\Models\Proposal;
use App\Modules\Proposal\Resources\ProposalResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ProposalController extends BaseController
{
    /**
     * Find all proposals
     *
     * Return a list of proposals. Allows pagination, relations and filter query.
     *
     * Authorization
     * - User with roles: any.
     */
    #[
        QueryParameter(
            name: "page[number]",
            type: "integer",
            description: "Current page number (default: 1)",
            example: 1,
        ),
    ]
    #[
        QueryParameter(
            name: "page[size]",
            type: "integer",
            description: "Size of current page (default: 15, max: 100)",
            example: 15,
        ),
    ]
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: position, contractType, educationLevel, experienceLevel, createdBy, reviewedBy </br>" .
                "Example: include=position,createdBy",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" .
                "Allow fields: status, title, createdByUserId, reviewedByUserId, positionId </br>" .
                "Example: filter[status]=APPROVED",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", Proposal::class);

        $proposals = QueryBuilder::for(Proposal::class)
            ->allowedIncludes([
                "position",
                "contractType",
                "educationLevel",
                "experienceLevel",
                "createdBy",
                "reviewedBy",
            ])
            ->allowedFilters([
                AllowedFilter::exact("status"),
                AllowedFilter::partial("title"),
                AllowedFilter::exact("createdByUserId", "created_by_user_id"),
                AllowedFilter::exact("reviewedByUserId", "reviewed_by_user_id"),
                AllowedFilter::exact("positionId", "position_id"),
            ])
            ->autoPaginate();

        return ProposalResourceCollection::make($proposals);
    }

    /**
     * Find proposal by Id
     *
     * Return a unique proposal. Allows relations query.
     *
     * Authorization
     * - User with roles: any.
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: position, contractType, educationLevel, experienceLevel, createdBy, reviewedBy </br>" .
                "Example: include=position,createdBy",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", Proposal::class);

        $proposal = QueryBuilder::for(Proposal::class)
            ->allowedIncludes([
                "position",
                "contractType",
                "educationLevel",
                "experienceLevel",
                "createdBy",
                "reviewedBy",
            ])
            ->findOrFail($id);

        return ProposalResource::make($proposal);
    }

    /**
     * Create proposal
     *
     * Return a unique proposal
     *
     * Authorization
     * - User with roles: MANAGER, HIRING_MANAGER.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ProposalStoreRequest $request)
    {
        $createdProposal = Proposal::create($request->validated());
        return $this->createdResponse(new ProposalResource($createdProposal));
    }

    /**
     * Update proposal
     *
     * Return no content
     *
     * Authorization
     * - User with roles: MANAGER, HIRING_MANAGER.
     * - User must be the creator of the proposal.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(ProposalUpdateRequest $request)
    {
        $proposalStatus = $request->proposal->status;

        if (!Collection::make([ProposalStatus::DRAFT, ProposalStatus::REJECTED])->contains($proposalStatus)) {
            throw new ConflictHttpException("Cannot delete. " . $proposalStatus->description());
        }

        $request->proposal->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Detele proposal
     *
     * Permanently delete proposal. Return no content.
     *
     * Authorization
     * - User with roles: MANAGER, HIRING_MANAGER.
     * - User must be the creator of the proposal.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(ProposalDestroyRequest $request)
    {
        $proposalStatus = $request->proposal->status;

        if (!Collection::make([ProposalStatus::DRAFT, ProposalStatus::REJECTED])->contains($proposalStatus)) {
            throw new ConflictHttpException("Cannot delete. " . $proposalStatus->description());
        }

        $request->proposal->delete();
        return $this->noContentResponse();
    }

    /**
     * Submit proposal
     *
     * Submit proposal for approval. Return no content.
     *
     * Authorization
     * - User with roles: MANAGER, HIRING_MANAGER.
     * - User must be the creator of the proposal.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function submit(ProposalSubmitRequest $request)
    {
        if ($request->proposal->status === ProposalStatus::PENDING) {
            throw new ConflictHttpException("Cannot submit. Proposal is already submitted.");
        }

        $request->proposal->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Reject proposal
     *
     * Reject PENDING proposal. Return no content.
     *
     * Authorization
     * - User with roles: EXECUTIVE.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function reject(ProposalRejectRequest $request)
    {
        if ($request->proposal->status === ProposalStatus::REJECTED) {
            throw new ConflictHttpException("Cannot reject. Proposal is already rejected.");
        }

        $request->proposal->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Approve proposal
     *
     * Approve PENDING proposal. Return no content.
     *
     * Authorization
     * - User with roles: EXECUTIVE.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function approve(ProposalApproveRequest $request)
    {
        if ($request->proposal->status === ProposalStatus::APPROVED) {
            throw new ConflictHttpException("Cannot approve. Proposal is already approved.");
        }

        $request->proposal->update($request->validated());
        return $this->noContentResponse();
    }
}
