<?php

namespace App\Modules\Candidate\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Candidate\Enums\CandidateStatus;
use App\Modules\Candidate\Models\CandidateExperience;
use App\Modules\Candidate\Requests\CandidateExperienceDestroyRequest;
use App\Modules\Candidate\Requests\CandidateExperienceStoreRequest;
use App\Modules\Candidate\Requests\CandidateExperienceUpdateRequest;
use App\Modules\Candidate\Resources\CandidateExperienceResource;
use App\Modules\Candidate\Resources\CandidateExperienceResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CandidateExperienceController extends BaseController
{
    /**
     * Find all candidate experiences
     *
     * Return a list of candidate experiences. Allows pagination, relations and filters query.
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
                " Allow relations: candidate </br>" .
                "Example: include=candidate",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" .
                "Allow fields: candidateId </br>" .
                "Example: filter[candidateId]=32",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", CandidateExperience::class);

        $candidateExperiences = QueryBuilder::for(CandidateExperience::class)
            ->allowedIncludes(["candidate"])
            ->allowedFilters([
                AllowedFilter::exact("candidateId", "candidate_id"),
                AllowedFilter::partial("employerName", "employer_name"),
                AllowedFilter::partial("positionTitle", "position_title"),
            ])
            ->autoPaginate();

        return CandidateExperienceResourceCollection::make($candidateExperiences);
    }

    /**
     * Find candidate experience by Id
     *
     * Return a unique candidate experience. Allows relations query.
     *
     * Authorization
     * - User can be anyone.
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: candidate </br>" .
                "Example: include=candidate",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", CandidateExperience::class);

        $candidateExperience = QueryBuilder::for(CandidateExperience::class)
            ->allowedIncludes(["candidate"])
            ->findOrFail($id);

        return $this->okResponse(new CandidateExperienceResource($candidateExperience));
    }

    /**
     * Create candidate experience
     *
     * Return created candidate experience.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CandidateExperienceStoreRequest $request)
    {
        $createdCandidateExperience = CandidateExperience::create($request->validated());
        return $this->createdResponse(new CandidateExperienceResource($createdCandidateExperience));
    }

    /**
     * Update candidate experience
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CandidateExperienceUpdateRequest $request)
    {
        $candidateStatus = $request->candidateExperience->candidate->status;

        if ($candidateStatus !== CandidateStatus::PENDING) {
            throw new ConflictHttpException("Cannot update. " . $candidateStatus->description());
        }

        $request->candidateExperience->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete candidate experience
     *
     * Permanently delete candidate experience. * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(CandidateExperienceDestroyRequest $request)
    {
        $candidateStatus = $request->candidateExperience->candidate->status;

        if ($candidateStatus !== CandidateStatus::PENDING) {
            throw new ConflictHttpException("Cannot delete. " . $candidateStatus->description());
        }

        $request->candidateExperience->delete();
        return $this->noContentResponse();
    }
}
