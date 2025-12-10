<?php

namespace App\Modules\Proposal\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Proposal\Enums\ProposalStatus;
use App\Modules\Proposal\Models\ProposalDocument;
use App\Modules\Proposal\Requests\ProposalDocumentDestroyRequest;
use App\Modules\Proposal\Requests\ProposalDocumentStoreRequest;
use App\Modules\Proposal\Requests\ProposalDocumentUpdateRequest;
use App\Modules\Proposal\Resources\ProposalDocumentResource;
use App\Modules\Proposal\Resources\ProposalDocumentResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ProposalDocumentController extends BaseController
{
    /**
     * Find all proposal documents
     *
     * Return a list of proposal documents. Allows pagination, relations and filter query.
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
                " Allow relations: proposal </br>" .
                "Example: include=position,createdBy",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" . "Allow fields: proposalId </br>" . "Example: filter[proposalId]=1",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", ProposalDocument::class);

        $proposalDocuments = QueryBuilder::for(ProposalDocument::class)
            ->allowedIncludes(["proposal"])
            ->allowedFilters([AllowedFilter::exact("proposalId", "proposal_id")])
            ->autoPaginate();

        return ProposalDocumentResourceCollection::make($proposalDocuments);
    }

    /**
     * Find proposal document by Id
     *
     * Return a unique proposal document. Allows relations query.
     *
     * Authorization
     * - User can be anyone.
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: proposal </br>" .
                "Example: include=position,createdBy",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", ProposalDocument::class);

        $proposalDocument = QueryBuilder::for(ProposalDocument::class)
            ->allowedIncludes(["proposal"])
            ->findOrFail($id);

        return ProposalDocumentResource::make($proposalDocument);
    }

    /**
     * Create proposal document
     *
     * Return a unique proposal document.
     *
     * Authorization
     * - User must be hiring manager or just manager.
     * - User must be the creator of the related proposal.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ProposalDocumentStoreRequest $request)
    {
        $file = $request->file("document");
        $fileExtension = $file->extension();
        $fileMimeType = $file->getClientMimeType();
        $fileInternalName = Str::uuid() . "." . $fileExtension;
        $fileOriginalName = $file->getClientOriginalName();
        $filePath = Storage::disk("public")->putFileAs("/", $file, $fileInternalName);
        $fileUrl = Storage::url($filePath);

        $createdProposalDocument = ProposalDocument::create([
            "file_id" => $fileInternalName,
            "file_name" => $fileOriginalName,
            "file_url" => $fileUrl,
            "file_extension" => $fileExtension,
            "mime_type" => $fileMimeType,
            "proposal_id" => $request->validated()["proposal_id"],
            "note" => $request->validated()["note"] ?? null,
        ]);

        return $this->createdResponse(new ProposalDocumentResource($createdProposalDocument));
    }

    /**
     * Update proposal document description
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager or just manager.
     * - User must be the creator of the related proposal.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(ProposalDocumentUpdateRequest $request)
    {
        $proposalStatus = $request->proposalDocument->proposal->status;

        if (!Collection::make([ProposalStatus::DRAFT, ProposalStatus::REJECTED])->contains($proposalStatus)) {
            throw new ConflictHttpException("Cannot update. " . $proposalStatus->description());
        }

        $request->proposalDocument->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Detele proposal document
     *
     * Permanently delete proposal document. Return no content
     *
     * Authorization
     * - User must be hiring manager or just manager.
     * - User must be the creator of the related proposal.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(ProposalDocumentDestroyRequest $request)
    {
        $proposalStatus = $request->proposalDocument->proposal->status;

        if (!Collection::make([ProposalStatus::DRAFT, ProposalStatus::REJECTED])->contains($proposalStatus)) {
            throw new ConflictHttpException("Cannot delete. " . $proposalStatus->description());
        }

        $request->proposalDocument->delete();
        return $this->noContentResponse();
    }
}
