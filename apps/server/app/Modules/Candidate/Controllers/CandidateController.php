<?php

namespace App\Modules\Candidate\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Candidate\Enums\CandidateStatus;
use App\Modules\Candidate\Requests\CandidateBlacklistRequest;
use App\Modules\Candidate\Requests\CandidateReactivateRequest;
use App\Modules\Candidate\Requests\CandidateStoreRequest;
use App\Modules\Candidate\Requests\CandidateUpdateRequest;
use App\Modules\Candidate\Models\Candidate;
use App\Modules\Candidate\Requests\CandidateDestroyRequest;
use App\Modules\Candidate\Resources\CandidateResource;
use App\Modules\Candidate\Resources\CandidateResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CandidateController extends BaseController
{
    /**
     * Find all candidates
     *
     * Return a list of candidates. Allows pagination, relations and filters query.
     *
     * Authorization
     * - User can be anyone.
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
                " Allow relations: hiringSource, experiences, documents </br>" .
                "Example: include=hiringSource,experiences",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" .
                "Allow fields: email, phoneNumber, status, hiringSourceId </br>" .
                "Example: filter[status]=EMPLOYED",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", Candidate::class);
        $candidates = QueryBuilder::for(Candidate::class)
            ->allowedIncludes(["hiringSource", "experiences", "documents"])
            ->allowedFilters([
                AllowedFilter::exact("email"),
                AllowedFilter::exact("phoneNumber", "phone_number"),
                AllowedFilter::exact("status"),
                AllowedFilter::exact("hiringSourceId", "hiring_source_id"),
            ])
            ->autoPaginate();

        return CandidateResourceCollection::make($candidates);
    }

    /**
     * Find candidate by Id
     *
     * Return a unique candidate. Allows relations query.
     *
     * Authorization
     * - User can be anyone.
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: hiringSource, experiences, documents </br>" .
                "Example: include=hiringSource,experiences",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", Candidate::class);

        $candidate = QueryBuilder::for(Candidate::class)
            ->allowedIncludes(["hiringSource", "experiences", "documents"])
            ->findOrFail($id);

        return CandidateResource::make($candidate);
    }

    /**
     * Create candidate
     *
     * Return created candidate.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CandidateStoreRequest $request)
    {
        $createdCandidate = Candidate::create($request->validated());
        return $this->createdResponse(CandidateResource::make($createdCandidate));
    }

    /**
     * Update candidate
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CandidateUpdateRequest $request)
    {
        $candidate = $request->getQueriedCandidateOrFail();

        if ($candidate->status !== CandidateStatus::READY) {
            throw new ConflictHttpException("Cannot update. " . $candidate->status->description());
        }

        $candidate->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete candidate
     *
     * Permanently delete candidate. * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(CandidateDestroyRequest $request)
    {
        $candidate = $request->getQueriedCandidateOrFail();

        if ($candidate->status !== CandidateStatus::READY) {
            throw new ConflictHttpException("Cannot delete. " . $candidate->status->description());
        }

        $candidate->delete();
        return $this->noContentResponse();
    }

    /**
     * Blacklist candidate
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function blacklist(CandidateBlacklistRequest $request)
    {
        $candidate = $request->getQueriedCandidateOrFail();

        if ($candidate->status === CandidateStatus::BLACKLISTED) {
            throw new ConflictHttpException("Cannot blacklist. Candidate is already blacklisted.");
        }

        $candidate->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Reactivate candidate
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function reactivate(CandidateReactivateRequest $request)
    {
        $candidate = $request->getQueriedCandidateOrFail();

        if ($candidate->status === CandidateStatus::READY) {
            throw new ConflictHttpException("Cannot reactivate. Candidate is already active.");
        }

        $candidate->update($request->validated());
        return $this->noContentResponse();
    }
}
