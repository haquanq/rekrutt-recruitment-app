<?php

namespace App\Modules\Candidate\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class CandidateDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "file_id" => $this->file_id,
            "file_name" => $this->file_name,
            "file_url" => URL::to("/") . $this->file_url,
            "file_exension" => $this->file_extension,
            "mime_type" => $this->mime_type,
            "note" => $this->note,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "candidate" => new CandidateResource($this->whenLoaded("candidate")),
        ];
    }
}
