<?php

namespace App\Modules\Proposal\Resources;

use App\Modules\Auth\Resources\UserResource;
use App\Modules\ContractType\Resources\ContractTypeResource;
use App\Modules\EducationLevel\Resources\EducationLevelResource;
use App\Modules\ExperienceLevel\Resources\ExperienceLevelResource;
use App\Modules\Position\Resources\PositionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProposalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "target_hires" => $this->target_hires,
            "total_hired" => $this->total_hired,
            "min_salary" => $this->min_salary,
            "max_salary" => $this->max_salary,
            "status" => $this->status,
            "reviewed_at" => $this->reviewed_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "contract_type" => new ContractTypeResource($this->whenLoaded("contractType")),
            "education_level" => new EducationLevelResource($this->whenLoaded("educationLevel")),
            "experience_level" => new ExperienceLevelResource($this->whenLoaded("experienceLevel")),
            "position" => new PositionResource($this->whenLoaded("experienceLevel")),
            "creator" => new UserResource($this->whenLoaded("creator")),
            "reviewer" => new UserResource($this->whenLoaded("reviewer")),
        ];
    }
}
