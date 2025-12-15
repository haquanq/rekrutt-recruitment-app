<?php

namespace App\Modules\Proposal\Resources;

use App\Modules\Auth\Resources\UserResource;
use App\Modules\ContractType\Resources\ContractTypeResource;
use App\Modules\EducationLevel\Resources\EducationLevelResource;
use App\Modules\ExperienceLevel\Resources\ExperienceLevelResource;
use App\Modules\Position\Resources\PositionResource;
use App\Modules\Recruitment\Resources\RecruitmentResource;
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
            "total_hires" => $this->total_hires,
            "min_salary" => $this->min_salary,
            "max_salary" => $this->max_salary,
            "status" => $this->status,
            "position" => PositionResource::make($this->whenLoaded("position")),
            "contract_type" => ContractTypeResource::make($this->whenLoaded("contractType")),
            "education_level" => EducationLevelResource::make($this->whenLoaded("educationLevel")),
            "experience_level" => ExperienceLevelResource::make($this->whenLoaded("experienceLevel")),
            "documents" => ProposalDocumentResource::collection($this->whenLoaded("documents")),
            "recruitments" => RecruitmentResource::collection($this->whenLoaded("recruitments")),
            "reviewed_by" => UserResource::make($this->whenLoaded("reviewedBy")),
            "reviewed_at" => $this->reviewed_at,
            "reviewed_notes" => $this->reviewed_notes,
            "created_by" => UserResource::make($this->whenLoaded("createdBy")),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
