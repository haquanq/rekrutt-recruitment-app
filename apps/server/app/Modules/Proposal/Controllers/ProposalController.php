<?php

namespace App\Modules\Proposal\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Proposal\Requests\ProposalStoreRequest;
use App\Modules\Proposal\Requests\ProposalUpdateRequest;
use App\Modules\Proposal\Resources\ProposalResource;
use App\Modules\Proposal\Models\Proposal;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProposalController extends BaseController
{
    public function index()
    {
        Gate::authorize("viewAny", Proposal::class);

        $proposals = QueryBuilder::for(Proposal::class)
            ->allowedIncludes([
                "candidate",
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
            ])
            ->get();

        return $this->okResponse(ProposalResource::collection($proposals));
    }

    public function show(int $id)
    {
        Gate::authorize("view", Proposal::class);

        $proposal = QueryBuilder::for(Proposal::class)
            ->allowedIncludes([
                "candidate",
                "position",
                "contractType",
                "educationLevel",
                "experienceLevel",
                "createdBy",
                "reviewedBy",
            ])
            ->findOrFail($id);

        return $this->okResponse(new ProposalResource($proposal));
    }

    public function store(ProposalStoreRequest $request)
    {
        Gate::authorize("create", Proposal::class);

        $createdProposal = Proposal::create($request->validated());
        return $this->createdResponse(new ProposalResource($createdProposal));
    }

    public function update(ProposalUpdateRequest $request, int $id)
    {
        Gate::authorize("update", Proposal::class);
        $proposal = Proposal::findOrFail($id);
        Gate::authorize("updateResource", $proposal);

        $proposal->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        $proposal = Proposal::findOrFail($id);
        Gate::authorize("delete", $proposal);
        $proposal->delete();
        return $this->noContentResponse();
    }
}
