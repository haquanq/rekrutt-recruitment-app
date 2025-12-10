<?php

namespace App\Modules\HiringSource\Controllers;

use App\Abstracts\BaseController;
use App\Modules\HiringSource\Requests\HiringSourceDestroyRequest;
use App\Modules\HiringSource\Requests\HiringSourceStoreRequest;
use App\Modules\HiringSource\Requests\HiringSourceUpdateRequest;
use App\Modules\HiringSource\Models\HiringSource;
use App\Modules\HiringSource\Resources\HiringSourceResource;
use App\Modules\HiringSource\Resources\HiringSourceResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class HiringSourceController extends BaseController
{
    /**
     * Find all hiring sources
     *
     * Return a list of hiring sources. Allows pagination and filter query.
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
            description: "Filter by fields </br>" . "Allow fields: name </br>" . "Example: filter[name]=LinkedIn",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", HiringSource::class);

        $hiringSources = QueryBuilder::for(HiringSource::class)
            ->allowedFilters([AllowedFilter::partial("name")])
            ->autoPaginate();

        return HiringSourceResourceCollection::make($hiringSources);
    }

    /**
     * Find hiring source by Id
     *
     * Return a unique hiring source.
     */
    public function show(int $id)
    {
        Gate::authorize("view", HiringSource::class);
        $hiringSource = HiringSource::findOrFail($id);
        return HiringSourceResource::make($hiringSource);
    }

    /**
     * Create hiring source
     *
     * Return created hiring source.
     *
     * Authorization
     * - User must be hiring manager or recruiter
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(HiringSourceStoreRequest $request)
    {
        $createdHiringSource = HiringSource::create($request->validated());
        return $this->createdResponse(new HiringSourceResource($createdHiringSource));
    }

    /**
     * Update hiring source
     *
     * Return no content
     *
     * Authorization
     * - User must be hiring manager or recruiter
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(HiringSourceUpdateRequest $request)
    {
        $request->getHiringSourceOrFail()->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete hiring source by Id
     *
     * Permanently delete hiring source. Return no content
     *
     * Authorization
     * - User must be hiring manager or recruiter
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(HiringSourceDestroyRequest $request)
    {
        $request->getHiringSourceOrFail()->delete();
        return $this->noContentResponse();
    }
}
