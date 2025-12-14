<?php

namespace App\Modules\Interview\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Interview\Enums\InterviewStatus;
use App\Modules\Interview\Models\InterviewEvaluation;
use App\Modules\Interview\Requests\InterviewEvaluationDestroyRequest;
use App\Modules\Interview\Requests\InterviewEvaluationStoreRequest;
use App\Modules\Interview\Requests\InterviewEvaluationUpdateRequest;
use App\Modules\Interview\Resources\InterviewEvaluationResource;
use App\Modules\Interview\Resources\InterviewEvaluationResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class InterviewEvaluationController extends BaseController
{
    /**
     * Find all interview evaluations
     *
     * Return a list of interview evaluations. Allows pagination, relations and filters query.
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
                " Allow relations: interview, createdBy </br>" .
                "Example: include=interview,createdBy",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" .
                "Allow fields: createdByUserId, interviewId </br>" .
                "Example: filter[interviewId]=14",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", InterviewEvaluation::class);

        $interviewMethods = QueryBuilder::for(InterviewEvaluation::class)
            ->allowedIncludes(["interview", "createdBy"])
            ->allowedFilters([
                AllowedFilter::exact("createdByUserId", "created_by_user_id"),
                AllowedFilter::exact("interviewId", "interview_id"),
            ])
            ->autoPaginate();

        return InterviewEvaluationResourceCollection::make($interviewMethods);
    }

    /**
     * Find interview evaluation by Id
     *
     * Return a unique interview evaluation. Allow relations query.
     *
     * Authorization
     * - User can be anyone.
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: interview, createdBy </br>" .
                "Example: include=interview,createdBy",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", InterviewEvaluation::class);

        $interviewEvaluation = QueryBuilder::for(InterviewEvaluation::class)
            ->allowedIncludes(["interview", "createdBy"])
            ->findOrFail($id);

        return InterviewEvaluationResource::make($interviewEvaluation);
    }

    /**
     * Create interview evaluation
     *
     * Return created interview evaluation.
     *
     * Authorization
     * - User can be anyone
     * - User must be participating in the interview.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(InterviewEvaluationStoreRequest $request)
    {
        $createdInterviewEvaluation = InterviewEvaluation::create($request->validated());
        return $this->createdResponse(InterviewEvaluationResource::make($createdInterviewEvaluation));
    }

    /**
     * Update interview evaluation
     *
     * Return no content.
     *
     * Authorization
     * - User can be anyone
     * - User must be the creator of the interview evaluation.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(InterviewEvaluationUpdateRequest $request)
    {
        $interviewEvaluation = $request->getQueriedInterviewEvaluationOrFail();
        $interview = $interviewEvaluation->interview;

        if ($interview->status !== InterviewStatus::UNDER_EVALUATION) {
            throw new ConflictHttpException("Cannot update. " . $interview->status->description());
        }

        $interviewEvaluation->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete interview evaluation by Id
     *
     * Permanently delete interview evaluation. * Return no content.
     *
     * Authorization
     * - User can be anyone
     * - User must be the creator of the interview evaluation.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(InterviewEvaluationDestroyRequest $request)
    {
        $interviewEvaluation = $request->getQueriedInterviewEvaluationOrFail();
        $interview = $interviewEvaluation->interview;

        if ($interview->status !== InterviewStatus::UNDER_EVALUATION) {
            throw new ConflictHttpException("Cannot delete. " . $interview->status->description());
        }

        $interviewEvaluation->delete();
        return $this->noContentResponse();
    }
}
