<?php

namespace App\Modules\Auth\Resources;

use App\Modules\Auth\Enums\UserRole;
use App\Modules\Position\Resources\PositionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $userIsAdmin = Auth::user()->role === UserRole::ADMIN;

        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email" => $this->email,
            "username" => $this->username,
            "phone_number" => $this->phone_number,
            "retired_at" => $this->retired_at,
            "suspension_started_at" => $this->when($userIsAdmin, $this->suspension_started_at),
            "suspension_ended_at" => $this->when($userIsAdmin, $this->suspension_ended_at),
            "suspension_note" => $this->when($userIsAdmin, $this->suspension_note),
            "role" => $this->role,
            "status" => $this->status,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "position" => PositionResource::make($this->whenLoaded("position")),
        ];
    }
}
