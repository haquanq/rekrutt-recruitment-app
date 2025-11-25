<?php

namespace App\Modules\Position\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Position\Requests\StorePositionRequest;
use App\Modules\Position\Requests\UpdatePositionRequest;
use App\Modules\Position\Models\Position;
use App\Modules\Position\Resources\PositionResource;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PositionController extends BaseController
{
    public function index()
    {
        Gate::authorize("findAll", Position::class);

        $positions = QueryBuilder::for(Position::class)
            ->allowedIncludes(["department"])
            ->allowedFilters([AllowedFilter::partial("title")])
            ->get();

        return $this->okResponse(PositionResource::collection(PositionResource::collection($positions)));
    }

    public function show(int $id)
    {
        Gate::authorize("findById", Position::class);

        $position = QueryBuilder::for(Position::class)
            ->allowedIncludes(["department"])
            ->get();

        return $this->okResponse(new PositionResource($position));
    }

    public function store(StorePositionRequest $request)
    {
        Gate::authorize("create", Position::class);
        $createdPosition = Position::create($request->validated());
        return $this->createdResponse(new PositionResource($createdPosition));
    }

    public function update(UpdatePositionRequest $request, int $id)
    {
        Gate::authorize("update", Position::class);
        Position::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        Gate::authorize("delete", Position::class);
        Position::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
