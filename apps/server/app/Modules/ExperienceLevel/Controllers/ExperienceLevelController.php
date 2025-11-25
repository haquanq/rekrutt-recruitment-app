<?php

namespace App\Modules\ExperienceLevel\Controllers;

use App\Abstracts\BaseController;
use App\Modules\ExperienceLevel\Models\ExperienceLevel;
use App\Modules\ExperienceLevel\Requests\StoreExperienceLevelRequest;
use App\Modules\ExperienceLevel\Requests\UpdateExperienceLevelRequest;
use App\Modules\ExperienceLevel\Resources\ExperienceLevelResource;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ExperienceLevelController extends BaseController
{
    public function index()
    {
        Gate::authorize("findAll", ExperienceLevel::class);
        $experienceLevels = QueryBuilder::for(ExperienceLevel::class)
            ->allowedFilters([AllowedFilter::partial("name")])
            ->get();

        return $this->okResponse(ExperienceLevelResource::collection($experienceLevels));
    }

    public function show(int $id)
    {
        Gate::authorize("findById", ExperienceLevel::class);
        $experienceLevel = ExperienceLevel::findOrFail($id);
        return $this->okResponse(new ExperienceLevelResource($experienceLevel));
    }

    public function store(StoreExperienceLevelRequest $request)
    {
        Gate::authorize("create", ExperienceLevel::class);
        $createdExperienceLevel = ExperienceLevel::create($request->validated());
        return response()->json($createdExperienceLevel);
    }

    public function update(UpdateExperienceLevelRequest $request, int $id)
    {
        Gate::authorize("update", ExperienceLevel::class);
        ExperienceLevel::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        Gate::authorize("delete", ExperienceLevel::class);
        ExperienceLevel::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
