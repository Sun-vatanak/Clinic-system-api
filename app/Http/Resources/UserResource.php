<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProfileResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => intval($this->id),
            'email' => $this->email,
            'is_active'=> intval($this->is_active),
            'telegram_id' => $this->telegram_id,

            'profile'=> new ProfileResource($this->profile),
            'role' =>$this->role_id ? [
                'id' => intval($this->role_id),
                'name' => $this->role->name ?? null
            ] : null,
            'created_at' => Carbon::parse($this->created_at)->isoFormat('YYYY-MM-DD HH:mm'),
            'updated_at' => Carbon::parse($this->updated_at)->isoFormat('YYYY-MM-DD HH:mm'),

        ];
    }
}
