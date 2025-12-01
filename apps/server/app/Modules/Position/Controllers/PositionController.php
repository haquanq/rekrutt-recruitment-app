<?php

namespace App\Modules\Position\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Position\Requests\PositionStoreReqeust;
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
     * Retrive a list of positions. Allows pagination, relations and filter query.
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
            // awdawdawd
            ->allowedFilters([AllowedFilter::partial("title"), AllowedFilter::exact("departmentId", "department_id")])
            ->autoPaginate();

        return PositionResourceCollection::make($positions);
    }

    /**
     * Find position by Id
     *
     * Return a unique position
     */
    public function show(int $id)
    {
        Gate::authorize("view", Position::class);
        $position = Position::findOrFail($id);
        return PositionResource::make($position);
    }

    /**
     * Create position
     *
     * Return created position
     */
    public function store(PositionStoreReqeust $request)
    {
        Gate::authorize("create", Position::class);
        $createdPosition = Position::create($request->validated());
        return $this->createdResponse(new PositionResource($createdPosition));
    }

    /**
     * Update position
     *
     * Update position information. Return empty
     */
    public function update(PositionUpdateRequest $request, int $id)
    {
        Gate::authorize("update", Position::class);
        Position::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete position by Id
     *
     * Permanently delete position. Return empty
     */
    public function destroy(int $id)
    {
        Gate::authorize("delete", Position::class);
        Position::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
