<?php

namespace App\Modules\EducationLevel\Controllers;

use App\Abstracts\BaseController;
use App\Modules\EducationLevel\Requests\EducationLevelStoreRequest;
use App\Modules\EducationLevel\Requests\EducationLevelUpdateRequest;
use App\Modules\EducationLevel\Models\EducationLevel;
use App\Modules\EducationLevel\Resources\EducationLevelResource;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EducationLevelController extends BaseController
{
    public function index()
    {
        Gate::authorize("viewAny", EducationLevel::class);

        $educationlevels = QueryBuilder::for(EducationLevel::class)
            ->allowedFilters([AllowedFilter::partial("name")])
            ->get();

        return $this->okResponse(EducationLevelResource::collection($educationlevels));
    }

    public function show(int $id)
    {
        Gate::authorize("view", EducationLevel::class);
        $educationLevel = EducationLevel::findOrFail($id);
        return $this->okResponse(new EducationLevelResource($educationLevel));
    }

    public function store(EducationLevelStoreRequest $request)
    {
        Gate::authorize("create", EducationLevel::class);
        $createdEducationlevel = EducationLevel::create($request->validated());
        return $this->createdResponse(new EducationLevelResource($createdEducationlevel));
    }

    public function update(EducationLevelUpdateRequest $request, int $id)
    {
        Gate::authorize("update", EducationLevel::class);
        EducationLevel::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        Gate::authorize("delete", EducationLevel::class);
        EducationLevel::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
