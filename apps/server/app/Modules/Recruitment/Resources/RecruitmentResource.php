<?php

namespace App\Modules\Recruitment\Resources;

use App\Modules\Auth\Resources\UserResource;
use App\Modules\Proposal\Resources\ProposalResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecruitmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "position_title" => $this->position_title,
            "scehduled_publish_at" => $this->scheduled_publish_at,
            "scheduled_close_at" => $this->scheduled_close_at,
            "actual_published_at" => $this->actual_published_at,
            "actual_closed_at" => $this->actual_closed_at,
            "completed_at" => $this->completed_at,
            "status" => $this->status,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "applications" => RecruitmentApplicationResource::collection($this->whenLoaded("applications")),
            "proposal" => ProposalResource::make($this->whenLoaded("proposal")),
            "createdBy" => UserResource::make($this->whenLoaded("createdBy`")),
            "closedBy" => UserResource::make($this->whenLoaded("closedBy`")),
        ];
    }
}
