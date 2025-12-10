<?php

namespace App\Modules\Department\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Department\Requests\DepartmentDestroyRequest;
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
     * Return a unique department. Allows relations query.
     *
     * Authorization
     * - User with roles: any
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
     * Return created department.
     *
     * Authorization
     * - User with roles: HIRING_MANAGER, RECRUITER
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(DepartmentStoreRequest $request)
    {
        $createdDepartment = Department::create($request->validated());
        return $this->createdResponse(DepartmentResource::make($createdDepartment));
    }

    /**
     * Update department
     *
     * Return no content.
     *
     * Authorization
     * - User with roles: HIRING_MANAGER, RECRUITER
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(DepartmentUpdateRequest $request)
    {
        $request->getDepartment()->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete department by Id
     *
     * Permanently delete department. Return no content.
     *
     * Authorization
     * - User with roles: HIRING_MANAGER, RECRUITER
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(DepartmentDestroyRequest $request)
    {
        $request->getDepartment()->delete();
        return $this->noContentResponse();
    }
}
