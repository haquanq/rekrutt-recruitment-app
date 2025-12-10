<?php

namespace App\Modules\Interview\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Interview\Models\InterviewMethod;
use App\Modules\Interview\Requests\InterviewMethodDestroyRequest;
use App\Modules\Interview\Requests\InterviewMethodStoreRequest;
use App\Modules\Interview\Requests\InterviewMethodUpdateRequest;
use App\Modules\Interview\Resources\InterviewMethodResource;
use App\Modules\Interview\Resources\InterviewMethodResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InterviewMethodController extends BaseController
{
    /**
     * Find all interview methods
     *
     * Return a list of interview methods. Allows pagination, relations and filters query.
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
        Gate::authorize("viewAny", InterviewMethod::class);

        $interviewMethods = QueryBuilder::for(InterviewMethod::class)
            ->allowedIncludes(["positions"])
            ->allowedFilters([AllowedFilter::partial("name")])
            ->autoPaginate();

        return InterviewMethodResourceCollection::make($interviewMethods);
    }

    /**
     * Find interview method by Id
     *
     * Return a unique interview method. Allow relations query.
     *
     * Authorization
     * - User can be anyone.
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
        Gate::authorize("view", InterviewMethod::class);

        $interviewMethod = QueryBuilder::for(InterviewMethod::class)
            ->allowedIncludes(["interviews"])
            ->findOrFail($id);

        return InterviewMethodResource::make($interviewMethod);
    }

    /**
     * Create interview method
     *
     * Return created interview method.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(InterviewMethodStoreRequest $request)
    {
        $createdInterviewMethod = InterviewMethod::create($request->validated());
        return $this->createdResponse(InterviewMethodResource::make($createdInterviewMethod));
    }

    /**
     * Update interview method
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(InterviewMethodUpdateRequest $request)
    {
        $request->interviewMethod->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete interview method by Id
     *
     * Permanently delete interview method. * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(InterviewMethodDestroyRequest $request)
    {
        $request->interviewMethod->delete();
        return $this->noContentResponse();
    }
}
