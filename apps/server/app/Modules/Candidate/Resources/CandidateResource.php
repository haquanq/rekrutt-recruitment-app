<?php

namespace App\Modules\Candidate\Resources;

use App\Modules\HiringSource\Resources\HiringSourceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "date_of_birth" => $this->date_of_birth,
            "email" => $this->email,
            "phone_number" => $this->phone_number,
            "address" => $this->address,
            "status" => $this->status,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "source" => new HiringSourceResource($this->whenLoaded("source")),
            "experiences" => CandidateExperienceResource::collection($this->whenLoaded("experiences")),
            "documents" => CandidateDocumentResource::collection($this->whenLoaded("documents")),
        ];
    }
}
