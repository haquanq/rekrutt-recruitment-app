<?php

namespace App\Modules\Candidate\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Candidate\Models\CandidateExperience;
use App\Modules\Candidate\Requests\StoreCandidateExperienceRequest;
use App\Modules\Candidate\Requests\UpdateCandidateExperienceRequest;
use App\Modules\Candidate\Resources\CandidateExperienceResource;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CandidateExperienceController extends BaseController
{
    public function index()
    {
        Gate::authorize("findAll", CandidateExperience::class);

        $candidateExperiences = QueryBuilder::for(CandidateExperience::class)
            ->allowedIncludes(["candidate"])
            ->allowedFilters([
                AllowedFilter::exact("candidateId", "candidate_id"),
                AllowedFilter::partial("employerName", "employer_name"),
            ])
            ->get();

        return $this->okResponse(CandidateExperienceResource::collection($candidateExperiences));
    }

    public function show(int $id)
    {
        Gate::authorize("findById", CandidateExperience::class);

        $candidateExperience = QueryBuilder::for(CandidateExperience::class)
            ->allowedIncludes(["candidate"])
            ->findOrFail($id);

        return $this->okResponse(new CandidateExperienceResource($candidateExperience));
    }

    public function store(StoreCandidateExperienceRequest $request)
    {
        Gate::authorize("create", CandidateExperience::class);
        $createdCandidateExperience = CandidateExperience::create($request->validated());
        return $this->createdResponse(new CandidateExperienceResource($createdCandidateExperience));
    }

    public function update(UpdateCandidateExperienceRequest $request, int $id)
    {
        Gate::authorize("update", CandidateExperience::class);
        CandidateExperience::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        Gate::authorize("delete", CandidateExperience::class);
        CandidateExperience::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
