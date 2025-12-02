<?php

namespace App\Modules\Proposal\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Proposal\Enums\ProposalStatus;
use App\Modules\Proposal\Requests\ProposalStoreRequest;
use App\Modules\Proposal\Requests\ProposalSubmitRequest;
use App\Modules\Proposal\Requests\ProposalUpdateRequest;
use App\Modules\Proposal\Resources\ProposalResource;
use App\Modules\Proposal\Models\Proposal;
use App\Modules\Proposal\Resources\ProposalResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProposalController extends BaseController
{
    /**
     * Find all proposals
     *
     * Return a list of proposals. Allows pagination, relations and filter query.
     *
     * Authorization rules:
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
     * Authorization rules:
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
     * Authorization rules:
     * - User with roles: MANAGER, HIRING_MANAGER.
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
     * Authorization rules:
     * - User with roles: MANAGER, HIRING_MANAGER.
     * - User must be the author of the proposal.
     */
    public function update(ProposalUpdateRequest $request)
    {
        $request->proposal->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Detele proposal
     *
     * Permanently delete proposal. Return no content
     *
     * Authorization rules:
     * - User with roles: MANAGER, HIRING_MANAGER.
     * - User must be the author of the proposal.
     */
    public function destroy(int $id)
    {
        $proposal = Proposal::findOrFail($id);
        Gate::authorize("delete", $proposal);
        $proposal->delete();
        return $this->noContentResponse();
    }

    /**
     * Submit proposal
     *
     * Submit proposal for approval. Return no content
     *
     * Authorization rules:
     * - User with roles: MANAGER, HIRING_MANAGER.
     * - User must be the author of the proposal.
     */
    public function submit(ProposalSubmitRequest $request)
    {
        if ($request->proposal->status === ProposalStatus::DRAFT) {
            $request->proposal->update($request->validated());
        }
        return $this->noContentResponse();
    }
}
