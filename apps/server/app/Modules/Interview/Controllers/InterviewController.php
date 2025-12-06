<?php

namespace App\Modules\Interview\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\Interview;
use App\Modules\Interview\Requests\InterviewCancelRequest;
use App\Modules\Interview\Requests\InterviewDestroyRequest;
use App\Modules\Interview\Requests\InterviewScheduleRequest;
use App\Modules\Interview\Requests\InterviewStoreRequest;
use App\Modules\Interview\Requests\InterviewUpdateRequest;
use App\Modules\Interview\Resources\InterviewResource;
use App\Modules\Interview\Resources\InterviewResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class InterviewController extends BaseController
{
    /**
     * Find all interviews
     *
     * Return a list of interviews. Allows pagination, relations and filter query.
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
                " Allow relations: interviews </br>" .
                "Example: include=interviews",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" . "Allow fields: name </br>" . "Example: filter[name]=Screening",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", Interview::class);

        $interviews = QueryBuilder::for(Interview::class)
            ->allowedIncludes([
                "method",
                "application",
                "createdBy",
                "cancelledBy",
                "evaluations",
                "participants",
                "ratingScale",
            ])
            ->allowedFilters([
                AllowedFilter::partial("title"),
                AllowedFilter::exact("status"),
                AllowedFilter::exact("recruitmentApplicationid", "recruitment_application_id"),
                AllowedFilter::exact("ratingScaleId", "rating_scale_id"),
                AllowedFilter::exact("interviewMethodId", "intermew_method_id"),
            ])
            ->autoPaginate();

        return InterviewResourceCollection::make($interviews);
    }

    /**
     * Find interview by Id
     *
     * Return a unique interview. Allow relations query.
     *
     * Authorization
     * - User with roles: any
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: interviews </br>" .
                "Example: include=interviews",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", Interview::class);

        $interview = QueryBuilder::for(Interview::class)
            ->allowedIncludes([
                "method",
                "application",
                "createdBy",
                "cancelledBy",
                "evaluations",
                "participants",
                "ratingScale",
            ])
            ->findOrFail($id);

        return InterviewResource::make($interview);
    }

    /**
     * Create new interview
     *
     * Return created interview.
     *
     * Authorization
     * - User with roles: HIRING_MANAGER, RECRUITER.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(InterviewStoreRequest $request)
    {
        $createdInterview = Interview::create($request->validated());
        return $this->createdResponse(new InterviewResource($createdInterview));
    }

    /**
     * Update interview
     *
     * Return no content.
     *
     * Authorization
     * - User with roles: HIRING_MANAGER, RECRUITER.
     * - User must be the creator of the interview.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(InterviewUpdateRequest $request)
    {
        $interviewStatus = $request->interview->status;

        if ($interviewStatus !== InterviewStatus::DRAFT) {
            throw new ConflictHttpException("Cannot update. " . $interviewStatus->description());
        }

        $request->interview->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete interview by Id
     *
     * Permanently delete interview. Return no content.
     *
     * Authorization
     * - User with roles: HIRING_MANAGER, RECRUITER.
     * - User must be the creator of the interview.
     */
    public function destroy(InterviewDestroyRequest $request)
    {
        $interviewStatus = $request->interview->status;

        if ($interviewStatus !== InterviewStatus::DRAFT) {
            throw new ConflictHttpException("Cannot delete. " . $interviewStatus->description());
        }

        $request->interview->delete();
        return $this->noContentResponse();
    }

    /**
     * Schedule interview
     *
     * Return no content.
     *
     * Authorization
     * - User with roles: HIRING_MANAGER, RECRUITER.
     * - User must be the creator of the interview.
     */
    public function schedule(InterviewScheduleRequest $request)
    {
        if ($request->interview->status === InterviewStatus::SCHEDULED) {
            throw new ConflictHttpException("Interview is already scheduled.");
        }

        $request->interview->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Cancel interview
     *
     * Return no content.
     *
     * Authorization
     * - User with roles: HIRING_MANAGER, RECRUITER.
     */
    public function cancel(InterviewCancelRequest $request)
    {
        if ($request->interview->status === InterviewStatus::CANCELLED) {
            throw new ConflictHttpException("Interview is already cancelled.");
        }

        $request->interview->update($request->validated());
        return $this->noContentResponse();
    }
}
