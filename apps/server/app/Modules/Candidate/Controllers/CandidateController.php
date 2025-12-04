<?php

namespace App\Modules\Candidate\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Candidate\Enums\CandidateStatus;
use App\Modules\Candidate\Requests\CandidateStoreRequest;
use App\Modules\Candidate\Requests\CandidateUpdateRequest;
use App\Modules\Candidate\Models\Candidate;
use App\Modules\Candidate\Resources\CandidateResource;
use App\Modules\Candidate\Resources\CandidateResourceCollection;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

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
            ->autoPaginate();

        return CandidateResourceCollection::make($candidates);
    }

    public function show(int $id)
    {
        Gate::authorize("view", Candidate::class);

        $candidate = QueryBuilder::for(Candidate::class)
            ->allowedIncludes(["hiringSource", "experiences", "documents"])
            ->findOrFail($id);

        return CandidateResource::make($candidate);
    }

    public function store(CandidateStoreRequest $request)
    {
        $createdCandidate = Candidate::create($request->validated());
        return $this->createdResponse(CandidateResource::make($createdCandidate));
    }

    public function update(CandidateUpdateRequest $request)
    {
        if ($request->candidate->status === CandidateStatus::PROCESSING) {
            throw new ConflictHttpException("Cannot update. Candidate is being processed.");
        }

        $request->candidate->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        Gate::authorize("delete", Candidate::class);
        $candidate = Candidate::findOrFail($id);

        if ($candidate->status === CandidateStatus::PROCESSING) {
            throw new ConflictHttpException("Cannot delete. Candidate is being processed.");
        }

        $candidate->delete();
        return $this->noContentResponse();
    }
}
