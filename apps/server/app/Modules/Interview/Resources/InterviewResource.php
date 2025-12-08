<?php

namespace App\Modules\Interview\Resources;

use App\Modules\Auth\Resources\UserResource;
use App\Modules\RatingScale\Resources\RatingScaleResource;
use App\Modules\Recruitment\Resources\RecruitmentApplicationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "started_at" => $this->started_at,
            "ended_at" => $this->ended_at,
            "completed_at" => $this->completed_at,
            "status" => $this->status,

            "method" => InterviewMethodResource::make($this->whenLoaded("method")),
            "rating_scale" => RatingScaleResource::make($this->whenLoaded("ratingScale")),

            "cancelled_at" => $this->cancelled_at,
            "cancelled_reason" => $this->cancelled_reason,
            "cancelled_by" => UserResource::make($this->whenLoaded("cancelledBy")),

            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "created_by" => UserResource::make($this->whenLoaded("createdBy")),

            "application" => RecruitmentApplicationResource::make($this->whenLoaded("application")),
            "evaluations" => InterviewEvaluationResource::make($this->whenLoaded("evaluations")),
            "participants" => UserResource::make($this->whenLoaded("interviewers")),
        ];
    }
}
