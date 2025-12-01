<?php

namespace App\Modules\Proposal\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Proposal\Models\ProposalDocument;
use App\Modules\Proposal\Requests\ProposalDocumentStoreRequest;
use App\Modules\Proposal\Requests\ProposalDocumentUpdateRequest;
use App\Modules\Proposal\Resources\ProposalDocumentResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Str;

class ProposalDocumentController extends BaseController
{
    public function index()
    {
        Gate::authorize("viewAny", ProposalDocument::class);

        $candidateDocuments = QueryBuilder::for(ProposalDocument::class)
            ->allowedIncludes(["proposal"])
            ->allowedFilters([AllowedFilter::exact("proposalId", "proposal_id")])
            ->get();

        return $this->okResponse(ProposalDocumentResource::collection($candidateDocuments));
    }

    public function show(int $id)
    {
        Gate::authorize("view", ProposalDocument::class);

        $candidateDocument = QueryBuilder::for(ProposalDocument::class)
            ->allowedIncludes(["candidate"])
            ->get();

        return new ProposalDocumentResource($candidateDocument);
    }

    public function store(ProposalDocumentStoreRequest $request)
    {
        Gate::authorize("create", ProposalDocument::class);

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
            "candidate_id" => $request->validated()["candidate_id"],
            "note" => $request->validated()["note"] ?? null,
        ]);

        return $this->createdResponse(new ProposalDocumentResource($createdProposalDocument));
    }

    public function update(ProposalDocumentUpdateRequest $request, int $id)
    {
        $proposalDocument = ProposalDocument::findOrFail($id)->load("proposal");
        Gate::authorize("update", $proposalDocument);
        $proposalDocument->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        $proposalDocument = ProposalDocument::findOrFail($id)->load("proposal");
        Gate::authorize("delete", $proposalDocument);
        $proposalDocument->delete();
        return $this->noContentResponse();
    }
}
