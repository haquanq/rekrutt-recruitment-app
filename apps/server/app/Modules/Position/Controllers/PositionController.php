<?php

namespace App\Modules\Position\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Position\Requests\StorePositionRequest;
use App\Modules\Position\Requests\UpdatePositionRequest;
use App\Modules\Position\Models\Position;
use App\Modules\Position\Resources\PositionResource;
use Illuminate\Support\Facades\Gate;

class PositionController extends BaseController
{
    public function index()
    {
        Gate::authorize("findAll", Position::class);
        $positions = Position::all();
        return $this->okResponse(PositionResource::collection(PositionResource::collection($positions)));
    }

    public function show(int $id)
    {
        Gate::authorize("findById", Position::class);
        $positions = Position::findOrFail($id);
        return $this->okResponse(new PositionResource($positions));
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
