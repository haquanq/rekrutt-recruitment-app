<?php

namespace App\Modules\Recruitment\Resources;

use App\Modules\Candidate\Resources\CandidateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecruitmentApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "completed_at" => $this->completed_at,
            "status" => $this->status,
            "priority" => $this->priority,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "recruitment" => RecruitmentResource::make($this->whenLoaded("recruitment")),
            "candidate" => CandidateResource::make($this->whenLoaded("candidate")),
        ];
    }
}
