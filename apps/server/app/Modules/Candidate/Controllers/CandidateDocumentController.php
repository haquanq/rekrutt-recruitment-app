<?php

namespace App\Modules\Candidate\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Candidate\Models\CandidateDocument;
use App\Modules\Candidate\Requests\CandidateDocumentStoreRequest;
use App\Modules\Candidate\Requests\CandidateDocumentUpdateRequest;
use App\Modules\Candidate\Resources\CandidateDocumentResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CandidateDocumentController extends BaseController
{
    public function index()
    {
        Gate::authorize("viewAny", CandidateDocument::class);

        $candidateDocuments = QueryBuilder::for(CandidateDocument::class)
            ->allowedIncludes(["candidate"])
            ->allowedFilters([AllowedFilter::exact("candidateId", "candidate_id")])
            ->get();

        return $this->okResponse(CandidateDocumentResource::collection($candidateDocuments));
    }

    public function show(int $id)
    {
        Gate::authorize("view", CandidateDocument::class);

        $candidateDocument = QueryBuilder::for(CandidateDocument::class)
            ->allowedIncludes(["candidate"])
            ->get();

        return new CandidateDocumentResource($candidateDocument);
    }

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

        return $this->createdResponse(new CandidateDocumentResource($createdCandidateDocument));
    }

    public function update(CandidateDocumentUpdateRequest $request, int $id)
    {
        Gate::authorize("update", CandidateDocument::class);
        CandidateDocument::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        Gate::authorize("delete", CandidateDocument::class);
        CandidateDocument::findOrFail($id)->delete($id);
        return $this->noContentResponse();
    }
}
