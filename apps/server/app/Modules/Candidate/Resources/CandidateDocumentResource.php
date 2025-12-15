<?php

namespace App\Modules\Candidate\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "file_url" => route("candidate-documents.download", $this->id),
            "file_name" => $this->file_name,
            "file_exension" => $this->file_extension,
            "mime_type" => $this->mime_type,
            "notes" => $this->notes,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "candidate" => new CandidateResource($this->whenLoaded("candidate")),
        ];
    }
}
