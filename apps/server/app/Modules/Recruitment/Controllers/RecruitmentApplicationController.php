<?php

namespace App\Modules\Recruitment\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Candidate\Enums\CandidateStatus;
use App\Modules\Candidate\Models\Candidate;
use App\Modules\Recruitment\Enums\RecruitmentApplicationStatus;
use App\Modules\Recruitment\Models\RecruitmentApplication;
use App\Modules\Recruitment\Requests\RecruitmentApplicationDestroyRequest;
use App\Modules\Recruitment\Requests\RecruitmentApplicationDiscardRequest;
use App\Modules\Recruitment\Requests\RecruitmentApplicationUpdateInterviewStatusRequest;
use App\Modules\Recruitment\Requests\RecruitmentApplicationUpdateOfferStatusRequest;
use App\Modules\Recruitment\Requests\RecruitmentApplicationStoreRequest;
use App\Modules\Recruitment\Requests\RecruitmentApplicationUpdatePriorityRequest;
use App\Modules\Recruitment\Requests\RecruitmentApplicationWithdrawRequest;
use App\Modules\Recruitment\Resources\RecruitmentApplicationResource;
use App\Modules\Recruitment\Resources\RecruitmentApplicationResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class RecruitmentApplicationController extends BaseController
{
    /**
     * Find all recruitment applications
     *
     * Return a list of recruitment applications. Allows pagination, relations and filter query.
     *
     * Authorization
     * - User can be anyone.
     */
    #[
        QueryParameter(
            name: "page[number]",
            type: "integer",
            description: "Current page number (default: 1)",
            example: 1,
        ),
    ]
    #[
        QueryParameter(
            name: "page[size]",
            type: "integer",
            description: "Size of current page (default: 15, max: 100)",
            example: 15,
        ),
    ]
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: recruitment, candidate, interviews, discardedBy </br>" .
                "Example: include=recruitment,candidate",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" .
                "Allow fields: status, priority, candidateId, recruitmentId </br>" .
                "Example: filter[status]=INTERVIEWING",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", RecruitmentApplication::class);

        $recruitmentApplications = QueryBuilder::for(RecruitmentApplication::class)
            ->allowedIncludes(["recruitment", "candidate", "interviews", "discardedBy"])
            ->allowedFilters([
                AllowedFilter::exact("status"),
                AllowedFilter::exact("priority"),
                AllowedFilter::exact("candidateId", "candidate_id"),
                AllowedFilter::exact("recruitmentId", "recruitment_id"),
            ])
            ->autoPaginate();

        return RecruitmentApplicationResourceCollection::make($recruitmentApplications);
    }

    /**
     * Find recruitment application by Id
     *
     * Return a unique recruitment application. Allow relations query.
     *
     * Authorization
     * - User can be anyone.
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: recruitment, candidate, interviews, discardedBy </br>" .
                "Example: include=recruitment,candidate",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", RecruitmentApplication::class);

        $recruitmentApplication = QueryBuilder::for(RecruitmentApplication::class)
            ->allowedIncludes(["recruitment", "candidate", "interviews", "discardedBy"])
            ->findOrFail($id);

        return RecruitmentApplicationResource::make($recruitmentApplication);
    }

    /**
     * Create recruitment application
     *
     * Return created recruitment application.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(RecruitmentApplicationStoreRequest $request)
    {
        $createdRecruitmentApplication = DB::transaction(function () use ($request) {
            $application = RecruitmentApplication::create($request->validated());
            Candidate::where("id", $application->candidate_id)->update(["status" => CandidateStatus::APPLYING->value]);
            return $application;
        });

        return $this->createdResponse(new RecruitmentApplicationResource($createdRecruitmentApplication));
    }

    /**
     * Delete recruitment application by Id
     *
     * Permanently delete recruitment application. * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(RecruitmentApplicationDestroyRequest $request)
    {
        $recruitmentApplication = $request->getQueriedRecruitmentApplicationOrFail();

        if ($recruitmentApplication->status !== RecruitmentApplicationStatus::PENDING->value) {
            throw new ConflictHttpException("Cannot delete. Recruitment application is processed.");
        }

        $recruitmentApplication->delete();
        return $this->noContentResponse();
    }

    /**
     * Update recruitment application priority
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function priority(RecruitmentApplicationUpdatePriorityRequest $request)
    {
        $request->getQueriedRecruitmentApplicationOrFail()->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Update recuitment application interview status
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function interview(RecruitmentApplicationUpdateInterviewStatusRequest $request)
    {
        $request->getQueriedRecruitmentApplicationOrFail()->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Update recuitment application offer status
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function offer(RecruitmentApplicationUpdateOfferStatusRequest $request)
    {
        $recruitmentApplication = $request->getQueriedRecruitmentApplicationOrFail();

        DB::transaction(function () use ($request, $recruitmentApplication) {
            $recruitmentApplication->update($request->validated());

            if ($recruitmentApplication->status === RecruitmentApplicationStatus::OFFER_ACCEPTED) {
                Candidate::where("id", $recruitmentApplication->candidate_id)->update([
                    "status" => CandidateStatus::EMPLOYED->value,
                    "employed_at" => Carbon::now(),
                ]);
            } elseif ($recruitmentApplication->status === RecruitmentApplicationStatus::OFFER_REJECTED) {
                Candidate::where("id", $recruitmentApplication->candidate_id)->update([
                    "status" => CandidateStatus::ARCHIVED->value,
                    "archived_at" => Carbon::now(),
                ]);
            }
        });

        return $this->noContentResponse();
    }

    /**
     * Discard recuitment application
     *
     * This is final decision made by organization. * Return no content.
     *
     * Reasons often are:
     * - Candidates did not move to the interview stage (ex: background check failed).
     * - Candidates did not pass one of the interviews.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function discard(RecruitmentApplicationDiscardRequest $request)
    {
        $recruitmentApplication = $request->getQueriedRecruitmentApplicationOrFail();

        if ($recruitmentApplication->status === RecruitmentApplicationStatus::DISCARDED->value) {
            throw new ConflictHttpException("Recruitment application is already discarded.");
        }

        DB::transaction(function () use ($recruitmentApplication, $request) {
            $recruitmentApplication->update($request->validated());
            Candidate::where("id", $recruitmentApplication->candidate_id)->update([
                "status" => CandidateStatus::ARCHIVED->value,
                "archived_at" => Carbon::now(),
            ]);
        });

        return $this->noContentResponse();
    }

    /**
     * Withdraw recuitment application
     *
     * This is final decision made by candidate. * Return no content.
     *
     * Reasons often are:
     * - Candidates changed their mind, got a better offer.
     * - Candidates didn't like the recruitment process, work conditions, etc.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function withdraw(RecruitmentApplicationWithdrawRequest $request)
    {
        $recruitmentApplication = $request->getQueriedRecruitmentApplicationOrFail();

        if ($recruitmentApplication->status === RecruitmentApplicationStatus::DISCARDED->value) {
            throw new ConflictHttpException("Recruitment application is already withdrawn.");
        }

        DB::transaction(function () use ($recruitmentApplication, $request) {
            $recruitmentApplication->update($request->validated());
            Candidate::where("id", $recruitmentApplication->candidate_id)->update([
                "status" => CandidateStatus::ARCHIVED->value,
                "archived_at" => Carbon::now(),
            ]);
        });

        return $this->noContentResponse();
    }
}
