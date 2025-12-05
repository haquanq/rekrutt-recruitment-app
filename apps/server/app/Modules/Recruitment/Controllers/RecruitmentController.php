<?php

namespace App\Modules\Recruitment\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Recruitment\Enums\RecruitmentStatus;
use App\Modules\Recruitment\Models\Recruitment;
use App\Modules\Recruitment\Requests\RecruitmentCloseRequest;
use App\Modules\Recruitment\Requests\RecruitmentDestroyRequest;
use App\Modules\Recruitment\Requests\RecruitmentPublishRequest;
use App\Modules\Recruitment\Requests\RecruitmentStoreRequest;
use App\Modules\Recruitment\Requests\RecruitmentUpdateRequest;
use App\Modules\Recruitment\Resources\RecruitmentResource;
use App\Modules\Recruitment\Resources\RecruitmentResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class RecruitmentController extends BaseController
{
    /**
     * Find all recruitments.
     *
     * Return a list of recruitments. Allows pagination, relations and filters query.
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
                " Allow relations: proposal, createdBy, closedBy, applications </br>" .
                "Example: include=proposal,createdBy",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" .
                "Allow fields: title, positionTitle, status, proposalId </br>" .
                "Example: filter[status]=PUBLISHED",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", Recruitment::class);

        $recruitments = QueryBuilder::for(Recruitment::class)
            ->allowedIncludes(["proposal", "createdBy", "closedBy", "applications"])
            ->allowedFilters([
                AllowedFilter::partial("title"),
                AllowedFilter::partial("positionTitle", "position_title"),
                AllowedFilter::exact("status"),
                AllowedFilter::exact("proposalId", "proposal_id"),
            ])
            ->autoPaginate();

        return RecruitmentResourceCollection::make($recruitments);
    }

    /**
     * Find recruitment by Id.
     *
     * Return a unique recruitment. Allow relations query.
     *
     * Authorization rules:
     * - User with roles: any.
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: proposal, createdBy, closedBy, applications </br>" .
                "Example: include=proposal,createdBy",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", Recruitment::class);

        $recruitment = QueryBuilder::for(Recruitment::class)
            ->allowedIncludes(["proposal", "createdBy", "closedBy", "applications"])
            ->findOrFail($id);

        return $this->okResponse(new RecruitmentResource($recruitment));
    }

    /**
     * Create recruitment.
     *
     * Return created recruitment.
     *
     * Authorization rules:
     * - User with roles: RECRUITER, HIRING_MANAGER.
     */
    public function store(RecruitmentStoreRequest $request)
    {
        $createdRecruitment = Recruitment::create($request->validated());
        return $this->createdResponse(new RecruitmentResource($createdRecruitment));
    }

    /**
     * Update recruitment.
     *
     * Return no content.
     *
     * Authorization rules:
     * - User with roles: RECRUITER, HIRING_MANAGER.
     * - User must be the creator of the recruitment.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(RecruitmentUpdateRequest $request, int $id)
    {
        if ($request->recruitment->status !== RecruitmentStatus::DRAFT) {
            throw new ConflictHttpException("Cannot update. " . $request->recruitment->status->description());
        }

        $request->recruitment->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete recruitment by Id.
     *
     * Permanently delete recruitment. Return no content.
     *
     * Authorization rules:
     * - User with roles: RECRUITER, HIRING_MANAGER.
     * - User must be the creator of the recruitment.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(RecruitmentDestroyRequest $request)
    {
        if ($request->recruitment->status !== RecruitmentStatus::DRAFT) {
            throw new ConflictHttpException("Cannot delete. " . $request->recruitment->status->description());
        }

        $request->recruitment->delete();
        return $this->noContentResponse();
    }

    /**
     * Publish recruitment.
     *
     * Return no content.
     *
     * Authorization rules:
     * - User with roles: RECRUITER, HIRING_MANAGER.
     * - User must be the creator of the recruitment.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function publish(RecruitmentPublishRequest $request)
    {
        if ($request->recruitment->status === RecruitmentStatus::PUBLISHED) {
            throw new ConflictHttpException("Recruitment is already published.");
        }

        $request->recruitment->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Close recruitment.
     *
     * Return no content.
     *
     * Authorization rules:
     * - User with roles: HIRING_MANAGER.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function close(RecruitmentCloseRequest $request)
    {
        if ($request->recruitment->status === RecruitmentStatus::CLOSED) {
            throw new ConflictHttpException("Recruitment is already closed.");
        }

        $request->recruitment->update($request->validated());
        return $this->noContentResponse();
    }
}
