<?php

namespace App\Modules\RatingScale\Resources;

use App\Modules\RatingScale\Models\RatingScalePoint;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingScaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "is_active" => $this->is_active,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "points" => RatingScalePointResource::collection($this->whenLoaded("points")),
        ];
    }
}
