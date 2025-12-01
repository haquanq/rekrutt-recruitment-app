<?php

namespace App\Modules\EducationLevel\Controllers;

use App\Abstracts\BaseController;
use App\Modules\EducationLevel\Requests\EducationLevelStoreRequest;
use App\Modules\EducationLevel\Requests\EducationLevelUpdateRequest;
use App\Modules\EducationLevel\Models\EducationLevel;
use App\Modules\EducationLevel\Resources\EducationLevelResource;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EducationLevelController extends BaseController
{
    /**
     * Find all education levels
     *
     * Return a list of education levels. Allows pagination and filter query.
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
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" . "Allow fields: name </br>" . "Example: filter[name]=Bachelor",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", EducationLevel::class);

        $educationlevels = QueryBuilder::for(EducationLevel::class)
            ->allowedFilters([AllowedFilter::partial("name")])
            ->get();

        return $this->okResponse(EducationLevelResource::collection($educationlevels));
    }

    /**
     * Find education level by Id
     *
     * Return a unique education level
     */
    public function show(int $id)
    {
        Gate::authorize("view", EducationLevel::class);
        $educationLevel = EducationLevel::findOrFail($id);
        return $this->okResponse(new EducationLevelResource($educationLevel));
    }

    /**
     * Create education level
     *
     * Return created education level
     */
    public function store(EducationLevelStoreRequest $request)
    {
        Gate::authorize("create", EducationLevel::class);
        $createdEducationlevel = EducationLevel::create($request->validated());
        return $this->createdResponse(new EducationLevelResource($createdEducationlevel));
    }

    /**
     * Update education level
     *
     * Return no content
     */
    public function update(EducationLevelUpdateRequest $request, int $id)
    {
        Gate::authorize("update", EducationLevel::class);
        EducationLevel::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete education level by Id
     *
     * Return no content
     */
    public function destroy(int $id)
    {
        Gate::authorize("delete", EducationLevel::class);
        EducationLevel::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
