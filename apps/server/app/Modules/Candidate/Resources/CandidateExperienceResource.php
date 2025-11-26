<?php

namespace App\Modules\Candidate\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateExperienceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "from_Date" => $this->from_date,
            "to_date" => $this->to_date,
            "employer_name" => $this->employer_name,
            "employer_description" => $this->employer_description,
            "position_title" => $this->position_title,
            "position_duty" => $this->position_duty,
            "note" => $this->note,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "candidate" => new CandidateResource($this->whenLoaded("candidate")),
        ];
    }
}
