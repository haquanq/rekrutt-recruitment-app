<?php

namespace App\Modules\Candidate\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Candidate\Requests\StoreCandidateRequest;
use App\Modules\Candidate\Requests\UpdateCandidateRequest;
use App\Modules\Candidate\Models\Candidate;
use App\Modules\Candidate\Resources\CandidateResource;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CandidateController extends BaseController
{
    public function index()
    {
        Gate::authorize("findAll", Candidate::class);
        $candidates = QueryBuilder::for(Candidate::class)
            ->allowedIncludes(["source", "experiences", "documents"])
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
        Gate::authorize("findById", Candidate::class);

        $candidate = QueryBuilder::for(Candidate::class)
            ->allowedIncludes(["source", "experiences", "documents"])
            ->findOrFail($id);

        return $this->okResponse(new CandidateResource($candidate));
    }

    public function store(StoreCandidateRequest $request)
    {
        Gate::authorize("create", Candidate::class);
        $createdCandidate = Candidate::create($request->validated());
        return $this->createdResponse(new CandidateResource($createdCandidate));
    }

    public function update(UpdateCandidateRequest $request, int $id)
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
