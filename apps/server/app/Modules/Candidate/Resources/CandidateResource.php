<?php

namespace App\Modules\Candidate\Resources;

use App\Modules\Auth\Resources\UserResource;
use App\Modules\HiringSource\Resources\HiringSourceResource;
use App\Modules\Recruitment\Resources\RecruitmentApplicationResource;
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
            "employed_at" => $this->employed_at,
            "archived_at" => $this->archived_at,
            "blacklisted_at" => $this->blacklisted_at,
            "blacklisted_reason" => $this->blacklisted_reason,
            "blacklisted_by" => UserResource::make($this->whenLoaded("blacklistedBy")),
            "reactivated_at" => $this->reactivated_at,
            "reactivated_reason" => $this->reactivated_reason,
            "reactivated_by" => UserResource::make($this->whenLoaded("reactivatedBy")),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "hiringSource" => new HiringSourceResource($this->whenLoaded("hiringSource")),
            "experiences" => CandidateExperienceResource::collection($this->whenLoaded("experiences")),
            "documents" => CandidateDocumentResource::collection($this->whenLoaded("documents")),
            "applications" => RecruitmentApplicationResource::collection($this->whenLoaded("applications")),
        ];
    }
}
