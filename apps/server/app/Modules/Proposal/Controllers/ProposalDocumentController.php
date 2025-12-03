<?php

namespace App\Modules\Proposal\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Proposal\Models\ProposalDocument;
use App\Modules\Proposal\Requests\ProposalDocumentStoreRequest;
use App\Modules\Proposal\Requests\ProposalDocumentUpdateRequest;
use App\Modules\Proposal\Resources\ProposalDocumentResource;
use App\Modules\Proposal\Resources\ProposalDocumentResourceCollection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProposalDocumentController extends BaseController
{
    public function index()
    {
        Gate::authorize("viewAny", ProposalDocument::class);

        $proposalDocuments = QueryBuilder::for(ProposalDocument::class)
            ->allowedIncludes(["proposal"])
            ->allowedFilters([AllowedFilter::exact("proposalId", "proposal_id")])
            ->autoPaginate();

        return ProposalDocumentResourceCollection::make($proposalDocuments);
    }

    public function show(int $id)
    {
        Gate::authorize("view", ProposalDocument::class);

        $proposalDocument = QueryBuilder::for(ProposalDocument::class)
            ->allowedIncludes(["proposal"])
            ->findOrFail($id);

        return ProposalDocumentResource::make($proposalDocument);
    }

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

    public function update(ProposalDocumentUpdateRequest $request)
    {
        $request->proposalDocument->update($request->validated());
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
