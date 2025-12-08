<?php

namespace App\Modules\Interview\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\InterviewParticipant;
use App\Modules\Interview\Requests\InterviewParticipantDestroyRequest;
use App\Modules\Interview\Requests\InterviewParticipantStoreRequest;
use App\Modules\Interview\Requests\InterviewParticipantUpdateRequest;
use App\Modules\Interview\Resources\InterviewParticipantResource;
use App\Modules\Interview\Resources\InterviewParticipantResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class InterviewParticipantController extends BaseController
{
    /**
     * Find all interview participants
     *
     * Return a list of interview participants. Allows pagination, relations and filter query.
     *
     * Authorization
     * - User with roles: any
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
                " Allow relations: interview, participant </br>" .
                "Example: include=interview,participant",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" .
                "Allow fields: interviewId, participantId </br>" .
                "Example: filter[interviewId]=2",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", InterviewParticipant::class);

        $interviewParticipants = QueryBuilder::for(InterviewParticipant::class)
            ->allowedIncludes(["interview", "participant"])
            ->allowedFilters([
                AllowedFilter::exact("interviewId", "interview_id"),
                AllowedFilter::exact("participantId", "user_id"),
            ])
            ->autoPaginate();

        return InterviewParticipantResourceCollection::make($interviewParticipants);
    }

    /**
     * Find interview participant by Id
     *
     * Return a unique interview participant. Allow relations query.
     *
     * Authorization
     * - User with roles: any
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: interview, participant </br>" .
                "Example: include=interview,participant",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", InterviewParticipant::class);

        $interviewParticipant = QueryBuilder::for(InterviewParticipant::class)
            ->allowedIncludes(["interview", "participant"])
            ->findOrFail($id);

        return InterviewParticipantResource::make($interviewParticipant);
    }

    /**
     * Create new interview participant
     *
     * Return created interview.
     *
     * Authorization
     * - User with roles: HIRING_MANAGER, RECRUITER.
     * - User must be the creator of the interview.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(InterviewParticipantStoreRequest $request)
    {
        $interviewStatus = $request->interview->status;

        if ($interviewStatus !== InterviewStatus::DRAFT) {
            throw new ConflictHttpException("Cannot create. " . $interviewStatus->description());
        }

        $createdInterviewParticipant = InterviewParticipant::create($request->validated());
        return $this->createdResponse(InterviewParticipantResource::make($createdInterviewParticipant));
    }

    /**
     * Update interview participant
     *
     * Return no content.
     *
     * Authorization
     * - User with roles: HIRING_MANAGER, RECRUITER.
     * - User must be the creator of the interview.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(InterviewParticipantUpdateRequest $request)
    {
        $interviewStatus = $request->interviewParticipant->interview->status;

        if ($interviewStatus !== InterviewStatus::DRAFT) {
            throw new ConflictHttpException("Cannot update. " . $interviewStatus->description());
        }

        $request->interview->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete interview participant by Id
     *
     * Permanently delete interview participant. Return no content.
     *
     * Authorization
     * - User with roles: HIRING_MANAGER, RECRUITER.
     * - User must be the creator of the interview.
     */
    public function destroy(InterviewParticipantDestroyRequest $request)
    {
        $interviewStatus = $request->interviewParticipant->interview->status;

        if ($interviewStatus !== InterviewStatus::DRAFT) {
            throw new ConflictHttpException("Cannot delete. " . $interviewStatus->description());
        }

        $request->interviewParticipant->delete();
        return $this->noContentResponse();
    }
}
