<?php

namespace App\Modules\RatingScale\Controllers;

use App\Abstracts\BaseController;
use App\Modules\RatingScale\Requests\RatingScalePointStoreRequest;
use App\Modules\RatingScale\Requests\RatingScalePointUpdateRequest;
use App\Modules\RatingScale\Models\RatingScalePoint;
use App\Modules\RatingScale\Resources\RatingScalePointResource;
use App\Modules\RatingScale\Resources\RatingScalePointResourceCollection;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;

class RatingScalePointController extends BaseController
{
    public function index()
    {
        Gate::authorize("viewAny", RatingScalePoint::class);

        $ratingScalePoints = QueryBuilder::for(RatingScalePoint::class)
            ->allowedIncludes(["ratingScale"])
            ->autoPaginate();

        return RatingScalePointResourceCollection::make($ratingScalePoints);
    }

    public function show(int $id)
    {
        Gate::authorize("view", RatingScalePoint::class);

        $ratingScalePoint = QueryBuilder::for(RatingScalePoint::class)
            ->allowedIncludes(["ratingScale"])
            ->findOrFail($id);

        return RatingScalePointResource::make($ratingScalePoint);
    }

    public function store(RatingScalePointStoreRequest $request)
    {
        Gate::authorize("create", RatingScalePoint::class);
        $createdRatingScalePoint = RatingScalePoint::create($request->validated());
        return $this->createdResponse(new RatingScalePointResource($createdRatingScalePoint));
    }

    public function update(RatingScalePointUpdateRequest $request, int $id)
    {
        Gate::authorize("update", RatingScalePoint::class);
        RatingScalePoint::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        Gate::authorize("delete", RatingScalePoint::class);
        RatingScalePoint::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
