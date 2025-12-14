<?php

namespace App\Modules\Candidate\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Candidate\Enums\CandidateStatus;
use App\Modules\Candidate\Models\CandidateDocument;
use App\Modules\Candidate\Requests\CandidateDocumentDestroyRequest;
use App\Modules\Candidate\Requests\CandidateDocumentStoreRequest;
use App\Modules\Candidate\Requests\CandidateDocumentUpdateRequest;
use App\Modules\Candidate\Resources\CandidateDocumentResource;
use App\Modules\Candidate\Resources\CandidateDocumentResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CandidateDocumentController extends BaseController
{
    /**
     * Find all candidate documents
     *
     * Return a list of candidate documents. Allows pagination, relations and filters query.
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
                " Allow relations: candidate </br>" .
                "Example: include=candidate",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" .
                "Allow fields: candidateId </br>" .
                "Example: filter[candidateId]=32",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", CandidateDocument::class);

        $candidateDocuments = QueryBuilder::for(CandidateDocument::class)
            ->allowedIncludes(["candidate"])
            ->allowedFilters([AllowedFilter::exact("candidateId", "candidate_id")])
            ->autoPaginate();

        return CandidateDocumentResourceCollection::make($candidateDocuments);
    }

    /**
     * Find candidate document by Id
     *
     * Return a unique candidate document. Allows relations query.
     *
     * Authorization
     * - User can be anyone.
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: candidate </br>" .
                "Example: include=candidate",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", CandidateDocument::class);

        $candidateDocument = QueryBuilder::for(CandidateDocument::class)
            ->allowedIncludes(["candidate"])
            ->get();

        return CandidateDocumentResource::make($candidateDocument);
    }

    /**
     * Create candidate document
     *
     * Return created candidate document.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CandidateDocumentStoreRequest $request)
    {
        $file = $request->file("document");
        $filePath = Storage::put("/candidate_documents", $file);

        $createdCandidateDocument = CandidateDocument::create([
            ...$request->validated(),
            "file_path" => $filePath,
            "file_name" => $file->getClientOriginalName(),
            "file_extension" => $file->extension(),
            "mime_type" => $file->getClientMimeType(),
        ]);

        return $this->createdResponse(CandidateDocumentResource::make($createdCandidateDocument));
    }

    /**
     * Update candidate document description
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CandidateDocumentUpdateRequest $request)
    {
        $candidateDocument = $request->getQueriedCandidateDocumentOrFail();
        $candidate = $candidateDocument->candidate;

        if ($candidate->status !== CandidateStatus::READY) {
            throw new ConflictHttpException("Cannot update. " . $candidate->status->description());
        }

        $candidateDocument->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete candidate document
     *
     * Permanently delete candidate document. * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(CandidateDocumentDestroyRequest $request)
    {
        $candidateDocument = $request->getQueriedCandidateDocumentOrFail();
        $candidate = $candidateDocument->candidate;

        if ($candidate->status !== CandidateStatus::READY) {
            throw new ConflictHttpException("Cannot delete. " . $candidate->status->description());
        }

        $candidateDocument->delete();
        Storage::delete($candidateDocument->file_path);
        return $this->noContentResponse();
    }
}
