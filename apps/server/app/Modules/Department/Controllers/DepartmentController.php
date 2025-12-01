<?php

namespace App\Modules\Department\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Department\Requests\DepartmentStoreRequest;
use App\Modules\Department\Requests\DepartmentUpdateRequest;
use App\Modules\Department\Models\Department;
use App\Modules\Department\Resources\DepartmentResource;
use App\Modules\Department\Resources\DepartmentResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DepartmentController extends BaseController
{
    /**
     * Find all departments
     *
     * Return a list of departments. Allows pagination, relations and filter query.
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
                " Allow relations: positions </br>" .
                "Example: include=positions",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" . "Allow fields: name </br>" . "Example: filter[name]=Human Resource",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", Department::class);

        $departments = QueryBuilder::for(Department::class)
            ->allowedIncludes(["positions"])
            ->allowedFilters([AllowedFilter::partial("name")])
            ->autoPaginate();

        return DepartmentResourceCollection::make($departments);
    }

    /**
     * Find department by Id
     *
     * Return a unique department
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: positions </br>" .
                "Example: include=positions",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", Department::class);

        $department = QueryBuilder::for(Department::class)
            ->allowedIncludes(["positions"])
            ->findOrFail($id);

        return DepartmentResource::make($department);
    }

    /**
     * Create department
     *
     * Return created department
     */
    public function store(DepartmentStoreRequest $request)
    {
        Gate::authorize("create", Department::class);
        $department = Department::create($request->validated());
        return $this->createdResponse(new DepartmentResource($department));
    }

    /**
     * Update department
     *
     * Return no content
     */
    public function update(DepartmentUpdateRequest $request, int $id)
    {
        Gate::authorize("update", Department::class);
        Department::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete department by Id
     *
     * Permanently delete department. Return no content
     */
    public function destroy(int $id)
    {
        Gate::authorize("delete", Department::class);
        Department::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
