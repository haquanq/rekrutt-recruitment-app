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
        $fileExtension = $file->extension();
        $fileMimeType = $file->getClientMimeType();
        $fileInternalName = Str::uuid() . "." . $fileExtension;
        $fileOriginalName = $file->getClientOriginalName();
        $filePath = Storage::disk("public")->putFileAs("/", $file, $fileInternalName);
        $fileUrl = Storage::url($filePath);

        $createdCandidateDocument = CandidateDocument::create([
            "file_id" => $fileInternalName,
            "file_name" => $fileOriginalName,
            "file_url" => $fileUrl,
            "file_extension" => $fileExtension,
            "mime_type" => $fileMimeType,
            "candidate_id" => $request->validated()["candidate_id"],
            "note" => $request->validated()["note"] ?? null,
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
        $candidateStatus = $request->candidateExperience->candidate->status;

        if ($candidateStatus !== CandidateStatus::PENDING) {
            throw new ConflictHttpException("Cannot update. " . $candidateStatus->description());
        }

        $request->candidateDocument->update($request->validated());
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
        $candidateStatus = $request->candidateExperience->candidate->status;

        if ($candidateStatus !== CandidateStatus::PENDING) {
            throw new ConflictHttpException("Cannot delete. " . $candidateStatus->description());
        }

        $request->candidateDocument->delete();
        return $this->noContentResponse();
    }
}
