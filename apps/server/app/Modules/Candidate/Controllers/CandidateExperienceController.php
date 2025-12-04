<?php

namespace App\Modules\Candidate\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Candidate\Models\CandidateExperience;
use App\Modules\Candidate\Requests\CandidateExperienceStoreRequest;
use App\Modules\Candidate\Requests\CandidateExperienceUpdateRequest;
use App\Modules\Candidate\Resources\CandidateExperienceResource;
use App\Modules\Candidate\Resources\CandidateExperienceResourceCollection;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CandidateExperienceController extends BaseController
{
    public function index()
    {
        Gate::authorize("viewAny", CandidateExperience::class);

        $candidateExperiences = QueryBuilder::for(CandidateExperience::class)
            ->allowedIncludes(["candidate"])
            ->allowedFilters([
                AllowedFilter::exact("candidateId", "candidate_id"),
                AllowedFilter::partial("employerName", "employer_name"),
                AllowedFilter::partial("positionTitle", "position_title"),
            ])
            ->autoPaginate();

        return CandidateExperienceResourceCollection::make($candidateExperiences);
    }

    public function show(int $id)
    {
        Gate::authorize("view", CandidateExperience::class);

        $candidateExperience = QueryBuilder::for(CandidateExperience::class)
            ->allowedIncludes(["candidate"])
            ->findOrFail($id);

        return $this->okResponse(new CandidateExperienceResource($candidateExperience));
    }

    public function store(CandidateExperienceStoreRequest $request)
    {
        $createdCandidateExperience = CandidateExperience::create($request->validated());
        return $this->createdResponse(new CandidateExperienceResource($createdCandidateExperience));
    }

    public function update(CandidateExperienceUpdateRequest $request)
    {
        $request->candidateExperience->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        Gate::authorize("delete", CandidateExperience::class);
        CandidateExperience::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
