<?php

namespace App\Modules\Candidate\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Candidate\Models\CandidateDocument;
use App\Modules\Candidate\Requests\StoreCandidateDocumentRequest;
use App\Modules\Candidate\Requests\UpdateCandidateDocumentRequest;
use App\Modules\Candidate\Resources\CandidateDocumentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Str;

class CandidateDocumentController extends BaseController
{
    public function index()
    {
        Gate::authorize("findAll", CandidateDocument::class);

        $candidateDocuments = QueryBuilder::for(CandidateDocument::class)
            ->allowedIncludes(["candidate"])
            ->allowedFilters([AllowedFilter::exact("candidateId", "candidate_id")])
            ->get();

        return $this->okResponse(CandidateDocumentResource::collection($candidateDocuments));
    }

    public function show(int $id)
    {
        Gate::authorize("findByid", CandidateDocument::class);

        $candidateDocument = QueryBuilder::for(CandidateDocument::class)
            ->allowedIncludes(["candidate"])
            ->get();

        return new CandidateDocumentResource($candidateDocument);
    }

    public function store(StoreCandidateDocumentRequest $request)
    {
        Gate::authorize("create", CandidateDocument::class);

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

    public function update(UpdateCandidateDocumentRequest $request, int $id)
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
