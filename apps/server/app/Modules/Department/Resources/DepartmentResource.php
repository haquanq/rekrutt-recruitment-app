<?php

namespace App\Modules\Department\Resources;

use App\Modules\Position\Resources\PositionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "positions" => PositionResource::collection($this->whenLoaded("positions")),
        ];
    }
}
