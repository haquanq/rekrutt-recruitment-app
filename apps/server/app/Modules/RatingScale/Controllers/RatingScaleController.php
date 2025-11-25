<?php

namespace App\Modules\RatingScale\Controllers;

use App\Abstracts\BaseController;
use App\Modules\RatingScale\Requests\StoreRatingScaleRequest;
use App\Modules\RatingScale\Requests\UpdateRatingScaleRequest;
use App\Modules\RatingScale\Models\RatingScale;
use App\Modules\RatingScale\Resources\RatingScaleResource;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RatingScaleController extends BaseController
{
    public function index()
    {
        Gate::authorize("findAll", RatingScale::class);

        $ratingScales = QueryBuilder::for(RatingScale::class)
            ->allowedIncludes(["points"])
            ->allowedFilters([AllowedFilter::partial("name")])
            ->get();

        return $this->okResponse(RatingScaleResource::collection(RatingScaleResource::collection($ratingScales)));
    }

    public function show(int $id)
    {
        Gate::authorize("findById", RatingScale::class);

        $ratingScale = QueryBuilder::for(RatingScale::class)
            ->allowedIncludes(["points"])
            ->get();

        return $this->okResponse(new RatingScaleResource($ratingScale));
    }

    public function store(StoreRatingScaleRequest $request)
    {
        Gate::authorize("create", RatingScale::class);
        $createdRatingScale = RatingScale::create($request->validated());
        return $this->createdResponse(new RatingScaleResource($createdRatingScale));
    }

    public function update(UpdateRatingScaleRequest $request, int $id)
    {
        Gate::authorize("update", RatingScale::class);
        RatingScale::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        Gate::authorize("delete", RatingScale::class);
        RatingScale::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
