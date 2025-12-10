<?php

namespace App\Modules\EducationLevel\Controllers;

use App\Abstracts\BaseController;
use App\Modules\EducationLevel\Requests\EducationLevelDestroyRequest;
use App\Modules\EducationLevel\Requests\EducationLevelStoreRequest;
use App\Modules\EducationLevel\Requests\EducationLevelUpdateRequest;
use App\Modules\EducationLevel\Models\EducationLevel;
use App\Modules\EducationLevel\Resources\EducationLevelResource;
use App\Modules\EducationLevel\Resources\EducationLevelResourceCollection;
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
            description: "Filter by fields </br>" . "Allow fields: name </br>" . "Example: filter[name]=Bachelor",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", EducationLevel::class);

        $educationlevels = QueryBuilder::for(EducationLevel::class)
            ->allowedFilters([AllowedFilter::partial("name")])
            ->autoPaginate();

        return EducationLevelResourceCollection::make($educationlevels);
    }

    /**
     * Find education level by Id
     *
     * Return a unique education level.
     *
     * Authorization
     * - User can be anyone.
     */
    public function show(int $id)
    {
        Gate::authorize("view", EducationLevel::class);
        $educationLevel = EducationLevel::findOrFail($id);
        return EducationLevelResource::make($educationLevel);
    }

    /**
     * Create education level
     *
     * Return created education level.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(EducationLevelStoreRequest $request)
    {
        $createdEducationlevel = EducationLevel::create($request->validated());
        return $this->createdResponse(EducationLevelResource::make($createdEducationlevel));
    }

    /**
     * Update education level
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(EducationLevelUpdateRequest $request)
    {
        $request->getEducationLevelOrFail()->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete education level by Id
     *
     * Permanently delete education level. * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(EducationLevelDestroyRequest $request)
    {
        $request->getEducationLevelOrFail()->delete();
        return $this->noContentResponse();
    }
}
