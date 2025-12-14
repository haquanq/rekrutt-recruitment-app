<?php

namespace App\Modules\Interview\Resources;

use App\Modules\Auth\Resources\UserResource;
use App\Modules\Interview\Resources\InterviewResource;
use App\Modules\RatingScale\Resources\RatingScalePointResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewEvaluationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "comment" => $this->comment,
            "point" => RatingScalePointResource::make($this->whenLoaded("point")),
            "interview" => InterviewResource::make($this->whenLoaded("interview")),
            "created_by" => UserResource::make($this->whenLoaded("createdBy")),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
