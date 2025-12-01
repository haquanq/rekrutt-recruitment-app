<?php

namespace App\Modules\Candidate\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Candidate\Requests\CandidateStoreRequest;
use App\Modules\Candidate\Requests\CandidateUpdateRequest;
use App\Modules\Candidate\Models\Candidate;
use App\Modules\Candidate\Resources\CandidateResource;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CandidateController extends BaseController
{
    public function index()
    {
        Gate::authorize("viewAny", Candidate::class);
        $candidates = QueryBuilder::for(Candidate::class)
            ->allowedIncludes(["hiringSource", "experiences", "documents"])
            ->allowedFilters([
                AllowedFilter::exact("email"),
                AllowedFilter::exact("phoneNumber", "phone_number"),
                AllowedFilter::exact("status"),
                AllowedFilter::exact("hiringSourceId", "hiring_source_id"),
            ])
            ->get();

        return $this->okResponse(CandidateResource::collection($candidates));
    }

    public function show(int $id)
    {
        Gate::authorize("view", Candidate::class);

        $candidate = QueryBuilder::for(Candidate::class)
            ->allowedIncludes(["hiringSource", "experiences", "documents"])
            ->findOrFail($id);

        return $this->okResponse(new CandidateResource($candidate));
    }

    public function store(CandidateStoreRequest $request)
    {
        Gate::authorize("create", Candidate::class);
        $createdCandidate = Candidate::create($request->validated());
        return $this->createdResponse(new CandidateResource($createdCandidate));
    }

    public function update(CandidateUpdateRequest $request, int $id)
    {
        Gate::authorize("update", Candidate::class);
        Candidate::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        Gate::authorize("delete", Candidate::class);
        Candidate::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
