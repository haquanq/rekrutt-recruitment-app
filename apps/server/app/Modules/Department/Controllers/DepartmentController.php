<?php

namespace App\Modules\Department\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Department\Requests\StoreDepartmentRequest;
use App\Modules\Department\Requests\UpdateDepartmentRequest;
use App\Modules\Department\Models\Department;
use App\Modules\Department\Resources\DepartmentResource;
use Illuminate\Support\Facades\Gate;

class DepartmentController extends BaseController
{
    public function index()
    {
        Gate::authorize("findAll", Department::class);
        $departments = Department::all();
        return $this->okResponse(DepartmentResource::collection($departments));
    }

    public function show(int $id)
    {
        Gate::authorize("findById", Department::class);
        $department = Department::findOrFail($id);
        return $this->okResponse(new DepartmentResource($department));
    }

    public function store(StoreDepartmentRequest $request)
    {
        Gate::authorize("create", Department::class);
        $department = Department::create($request->validated());
        return $this->createdResponse(new DepartmentResource($department));
    }

    public function update(UpdateDepartmentRequest $request, int $id)
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
