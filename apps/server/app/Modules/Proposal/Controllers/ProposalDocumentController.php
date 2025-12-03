<?php

namespace App\Modules\Proposal\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Proposal\Enums\ProposalStatus;
use App\Modules\Proposal\Models\ProposalDocument;
use App\Modules\Proposal\Requests\ProposalDocumentStoreRequest;
use App\Modules\Proposal\Requests\ProposalDocumentUpdateRequest;
use App\Modules\Proposal\Resources\ProposalDocumentResource;
use App\Modules\Proposal\Resources\ProposalDocumentResourceCollection;
use Dedoc\Scramble\Attributes\Response as OpenApiResponse;
use Dedoc\Scramble\Attributes\QueryParameter;
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
     * Authorization rules:
     * - User with roles: any.
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
     * Authorization rules:
     * - User with roles: any.
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
     * Return a unique proposal document
     *
     * Authorization rules:
     * - User with roles: MANAGER, HIRING_MANAGER.
     * - User must be the author of the related proposal.
     *
     */
    #[OpenApiResponse(403, description: "Authorization error", type: AuthorizationException::class)]
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
     * Return no content
     *
     * Authorization rules:
     * - User with roles: MANAGER, HIRING_MANAGER.
     * - User must be the author of the related proposal.
     */
    #[OpenApiResponse(403, description: "Authorization error", type: AuthorizationException::class)]
    public function update(ProposalDocumentUpdateRequest $request)
    {
        if ($request->proposalDocument->proposal->status === ProposalStatus::PENDING) {
            throw new ConflictHttpException("Cannot update. Proposal of this document is pending for approval.");
        }

        if ($request->proposalDocument->proposal->status === ProposalStatus::APPROVED) {
            throw new ConflictHttpException("Cannot update. Proposal of this document is approved.");
        }

        $request->proposalDocument->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Detele proposal document
     *
     * Permanently delete proposal document. Return no content
     *
     * Authorization rules:
     * - User with roles: MANAGER, HIRING_MANAGER.
     * - User must be the author of the related proposal.
     */
    public function destroy(int $id)
    {
        $proposalDocument = ProposalDocument::with("proposal")->findOrFail($id);

        Gate::authorize("delete", $proposalDocument);

        if ($proposalDocument->proposal->status === ProposalStatus::PENDING) {
            throw new ConflictHttpException("Cannot delete. Proposal of this document is pending for approval.");
        }

        if ($proposalDocument->proposal->status === ProposalStatus::APPROVED) {
            throw new ConflictHttpException("Cannot delete. Proposal of this document is approved.");
        }

        $proposalDocument->delete();
        return $this->noContentResponse();
    }
}
