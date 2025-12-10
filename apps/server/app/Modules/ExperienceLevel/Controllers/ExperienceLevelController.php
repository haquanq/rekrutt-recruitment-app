<?php

namespace App\Modules\ExperienceLevel\Controllers;

use App\Abstracts\BaseController;
use App\Modules\ExperienceLevel\Models\ExperienceLevel;
use App\Modules\ExperienceLevel\Requests\ExperienceLevelDestroyRequest;
use App\Modules\ExperienceLevel\Requests\ExperienceLevelStoreRequest;
use App\Modules\ExperienceLevel\Requests\ExperienceLevelUpdateRequest;
use App\Modules\ExperienceLevel\Resources\ExperienceLevelResource;
use App\Modules\ExperienceLevel\Resources\ExperienceLevelResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ExperienceLevelController extends BaseController
{
    /**
     * Find all experience levels
     *
     * Return a list of experience levels. Allows pagination and filter query.
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
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" . "Allow fields: name </br>" . "Example: filter[name]=Fresher",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", ExperienceLevel::class);
        $experienceLevels = QueryBuilder::for(ExperienceLevel::class)
            ->allowedFilters([AllowedFilter::partial("name")])
            ->autoPaginate();

        return ExperienceLevelResourceCollection::make($experienceLevels);
    }

    /**
     * Find experience level by Id
     *
     * Return a unique experience level.
     *
     * Authorization
     * - User can be anyone.
     */
    public function show(int $id)
    {
        Gate::authorize("view", ExperienceLevel::class);
        $experienceLevel = ExperienceLevel::findOrFail($id);
        return ExperienceLevelResource::make($experienceLevel);
    }

    /**
     * Create experience level
     *
     * Return created experience level.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ExperienceLevelStoreRequest $request)
    {
        $createdExperienceLevel = ExperienceLevel::create($request->validated());
        return response()->json(ExperienceLevelResource::make($createdExperienceLevel));
    }

    /**
     * Update experience level
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(ExperienceLevelUpdateRequest $request)
    {
        $request->getExperienceLevelOrFail()->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete experience level by Id
     *
     * Permanently delete experience level. Return no content
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(ExperienceLevelDestroyRequest $request)
    {
        $request->getExperienceLevelOrFail()->delete();
        return $this->noContentResponse();
    }
}
