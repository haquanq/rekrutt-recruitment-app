<?php

namespace App\Modules\Recruitment\Resources;

use App\Modules\Auth\Resources\UserResource;
use App\Modules\Candidate\Resources\CandidateResource;
use App\Modules\Interview\Resources\InterviewResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecruitmentApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "status" => $this->status,
            "priority" => $this->priority,
            "number_of_interviews" => $this->number_of_interviews,
            "offer_started_at" => $this->offer_started_at,
            "offer_expired_at" => $this->offer_expired_at,
            "offer_responded_at" => $this->offer_responded_at,
            "offer_rejected_reason" => $this->offer_rejected_reason,
            "discarded_at" => $this->discarded_at,
            "discarded_reason" => $this->discarded_reason,
            "discarded_by" => UserResource::make($this->whenLoaded("discardedBy")),
            "withdrawn_at" => $this->withdrawn_at,
            "withdrawn_reason" => $this->withdrawn_reason,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "recruitment" => RecruitmentResource::make($this->whenLoaded("recruitment")),
            "candidate" => CandidateResource::make($this->whenLoaded("candidate")),
            "interviews" => InterviewResource::collection($this->whenLoaded("interviews")),
        ];
    }
}
