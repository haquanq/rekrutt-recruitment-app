<?php

namespace App\Modules\RatingScale\Controllers;

use App\Abstracts\BaseController;
use App\Modules\RatingScale\Requests\RatingScaleStoreRequest;
use App\Modules\RatingScale\Requests\RatingScaleUpdateRequest;
use App\Modules\RatingScale\Models\RatingScale;
use App\Modules\RatingScale\Resources\RatingScaleResource;
use App\Modules\RatingScale\Resources\RatingScaleResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RatingScaleController extends BaseController
{
    /**
     * Find all rating scales
     *
     * Return a list of rating scales. Allows pagination, relations and filter query.
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
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: points </br>" .
                "Example: include=points",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" .
                "Allow fields: name, isActive </br>" .
                "Example: filter[isActive]=true",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", RatingScale::class);

        $ratingScales = QueryBuilder::for(RatingScale::class)
            ->allowedIncludes(["points"])
            ->allowedFilters([AllowedFilter::partial("name"), AllowedFilter::exact("isActive", "is_active")])
            ->autoPaginate();

        return RatingScaleResourceCollection::make($ratingScales);
    }

    /**
     * Find rating scale by Id
     *
     * Return a unique rating scale. Allow relations query.
     *
     * Authorization
     * - User can be anyone.
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: points </br>" .
                "Example: include=points",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", RatingScale::class);

        $ratingScale = QueryBuilder::for(RatingScale::class)
            ->allowedIncludes(["points"])
            ->findOrFail($id);

        return RatingScaleResource::make($ratingScale);
    }

    /**
     * Create rating scale
     *
     * Return created rating scale.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(RatingScaleStoreRequest $request)
    {
        Gate::authorize("create", RatingScale::class);
        $createdRatingScale = RatingScale::create($request->validated());
        return $this->createdResponse(new RatingScaleResource($createdRatingScale));
    }

    /**
     * Update rating scale
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(RatingScaleUpdateRequest $request, int $id)
    {
        Gate::authorize("update", RatingScale::class);
        RatingScale::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete rating scale by Id
     *
     * Permanently delete rating scale. Return no content
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $id)
    {
        Gate::authorize("delete", RatingScale::class);
        RatingScale::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
