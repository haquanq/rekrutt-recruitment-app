<?php

namespace App\Modules\ExperienceLevel\Controllers;

use App\Abstracts\BaseController;
use App\Modules\ExperienceLevel\Models\ExperienceLevel;
use App\Modules\ExperienceLevel\Requests\ExperienceLevelStoreRequest;
use App\Modules\ExperienceLevel\Requests\ExperienceLevelUpdateRequest;
use App\Modules\ExperienceLevel\Resources\ExperienceLevelResource;
use App\Modules\ExperienceLevel\Resources\ExperienceLevelResourceCollection;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ExperienceLevelController extends BaseController
{
    public function index()
    {
        Gate::authorize("viewAny", ExperienceLevel::class);
        $experienceLevels = QueryBuilder::for(ExperienceLevel::class)
            ->allowedFilters([AllowedFilter::partial("name")])
            ->autoPaginate();

        return ExperienceLevelResourceCollection::make($experienceLevels);
    }

    public function show(int $id)
    {
        Gate::authorize("view", ExperienceLevel::class);
        $experienceLevel = ExperienceLevel::findOrFail($id);
        return ExperienceLevelResource::make($experienceLevel);
    }

    public function store(ExperienceLevelStoreRequest $request)
    {
        Gate::authorize("create", ExperienceLevel::class);
        $createdExperienceLevel = ExperienceLevel::create($request->validated());
        return response()->json($createdExperienceLevel);
    }

    public function update(ExperienceLevelUpdateRequest $request, int $id)
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
