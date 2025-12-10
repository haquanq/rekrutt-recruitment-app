<?php

namespace App\Modules\RatingScale\Controllers;

use App\Abstracts\BaseController;
use App\Modules\RatingScale\Requests\RatingScalePointStoreRequest;
use App\Modules\RatingScale\Requests\RatingScalePointUpdateRequest;
use App\Modules\RatingScale\Models\RatingScalePoint;
use App\Modules\RatingScale\Resources\RatingScalePointResource;
use App\Modules\RatingScale\Resources\RatingScalePointResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RatingScalePointController extends BaseController
{
    /**
     * Find all rating scale points
     *
     * Return a list of rating scale points. Allows pagination, relations and filter query.
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
                " Allow relations: ratingScale </br>" .
                "Example: include=ratingScale",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" .
                "Allow fields: ratingScaleId </br>" .
                "Example: filter[ratingScaleId]=1",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", RatingScalePoint::class);

        $ratingScalePoints = QueryBuilder::for(RatingScalePoint::class)
            ->allowedIncludes(["ratingScale"])
            ->allowedFilters([AllowedFilter::exact("ratingScaleId", "rating_scale_id")])
            ->autoPaginate();

        return RatingScalePointResourceCollection::make($ratingScalePoints);
    }

    /**
     * Find rating scale point by Id
     *
     * Return a unique rating scale point. Allow relations query.
     *
     * Authorization
     * - User can be anyone.
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: ratingScale </br>" .
                "Example: include=ratingScale",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", RatingScalePoint::class);

        $ratingScalePoint = QueryBuilder::for(RatingScalePoint::class)
            ->allowedIncludes(["ratingScale"])
            ->findOrFail($id);

        return RatingScalePointResource::make($ratingScalePoint);
    }

    /**
     * Create rating scale point
     *
     * Return created rating scale point.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     */
    public function store(RatingScalePointStoreRequest $request)
    {
        Gate::authorize("create", RatingScalePoint::class);
        $createdRatingScalePoint = RatingScalePoint::create($request->validated());
        return $this->createdResponse(new RatingScalePointResource($createdRatingScalePoint));
    }

    /**
     * Update rating scale point
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(RatingScalePointUpdateRequest $request, int $id)
    {
        Gate::authorize("update", RatingScalePoint::class);
        RatingScalePoint::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete rating scale point by Id
     *
     * Permanently delete rating scale point. Return no content
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $id)
    {
        Gate::authorize("delete", RatingScalePoint::class);
        RatingScalePoint::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
