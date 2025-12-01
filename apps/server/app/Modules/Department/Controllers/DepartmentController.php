<?php

namespace App\Modules\Department\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Department\Requests\DepartmentStoreRequest;
use App\Modules\Department\Requests\DepartmentUpdateRequest;
use App\Modules\Department\Models\Department;
use App\Modules\Department\Resources\DepartmentResource;
use App\Modules\Department\Resources\DepartmentResourceCollection;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DepartmentController extends BaseController
{
    public function index()
    {
        Gate::authorize("viewAny", Department::class);

        $departments = QueryBuilder::for(Department::class)
            ->allowedIncludes(["positions"])
            ->allowedFilters([AllowedFilter::partial("name")])
            ->autoPaginate();

        return DepartmentResourceCollection::make($departments);
    }

    public function show(int $id)
    {
        Gate::authorize("view", Department::class);

        $department = QueryBuilder::for(Department::class)
            ->allowedIncludes(["positions"])
            ->findOrFail($id);

        return DepartmentResource::make($department);
    }

    public function store(DepartmentStoreRequest $request)
    {
        Gate::authorize("create", Department::class);
        $department = Department::create($request->validated());
        return $this->createdResponse(new DepartmentResource($department));
    }

    public function update(DepartmentUpdateRequest $request, int $id)
    {
        Gate::authorize("update", Department::class);
        Department::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        Gate::authorize("delete", Department::class);
        Department::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
