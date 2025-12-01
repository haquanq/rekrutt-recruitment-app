<?php

namespace App\Modules\HiringSource\Controllers;

use App\Abstracts\BaseController;
use App\Modules\HiringSource\Requests\HiringSourceStoreRequest;
use App\Modules\HiringSource\Requests\HiringSourceUpdateRequest;
use App\Modules\HiringSource\Models\HiringSource;
use App\Modules\HiringSource\Resources\HiringSourceResource;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class HiringSourceController extends BaseController
{
    public function index()
    {
        Gate::authorize("viewAny", HiringSource::class);

        $hiringSources = QueryBuilder::for(HiringSource::class)
            ->allowedFilters([AllowedFilter::partial("name")])
            ->get();

        return $this->okResponse(HiringSourceResource::collection($hiringSources));
    }

    public function show(int $id)
    {
        Gate::authorize("view", HiringSource::class);
        $hiringSource = HiringSource::findOrFail($id);
        return $this->okResponse(new HiringSourceResource($hiringSource));
    }

    public function store(HiringSourceStoreRequest $request)
    {
        Gate::authorize("create", HiringSource::class);
        $createdHiringSource = HiringSource::create($request->validated());
        return $this->createdResponse(new HiringSourceResource($createdHiringSource));
    }

    public function update(HiringSourceUpdateRequest $request, int $id)
    {
        Gate::authorize("update", HiringSource::class);
        HiringSource::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        Gate::authorize("delete", HiringSource::class);
        HiringSource::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
