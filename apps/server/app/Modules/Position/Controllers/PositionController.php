<?php

namespace App\Modules\Position\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Position\Requests\PositionDestroyRequest;
use App\Modules\Position\Requests\PositionStoreRequest;
use App\Modules\Position\Requests\PositionUpdateRequest;
use App\Modules\Position\Models\Position;
use App\Modules\Position\Resources\PositionResource;
use App\Modules\Position\Resources\PositionResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PositionController extends BaseController
{
    /**
     * Find all positions
     *
     * Return a list of positions. Allows pagination, relations and filter query.
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
                " Allow relations: department </br>" .
                "Example: include=department",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" .
                "Allow fields: title, departmentId </br>" .
                "Example: filter[title]=Software Engineer",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", Position::class);

        $positions = QueryBuilder::for(Position::class)
            ->allowedIncludes(["department"])
            ->allowedFilters([AllowedFilter::partial("title"), AllowedFilter::exact("departmentId", "department_id")])
            ->autoPaginate();

        return PositionResourceCollection::make($positions);
    }

    /**
     * Find position by Id
     *
     * Return a unique position. Allow relations query.
     *
     * Authorization
     * - User can be anyone.
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: department </br>" .
                "Example: include=department",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", Position::class);

        $position = QueryBuilder::for(Position::class)
            ->allowedIncludes(["department"])
            ->findOrFail($id);

        return PositionResource::make($position);
    }

    /**
     * Create position
     *
     * Return created position.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(PositionStoreRequest $request)
    {
        $createdPosition = Position::create($request->validated());
        return $this->createdResponse(new PositionResource($createdPosition));
    }

    /**
     * Update position
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(PositionUpdateRequest $request)
    {
        $request->getQueriedPositionOrFail()->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete position by Id
     *
     * Permanently delete position. Return no content
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(PositionDestroyRequest $request)
    {
        $request->getQueriedPositionOrFail()->delete();
        return $this->noContentResponse();
    }
}
