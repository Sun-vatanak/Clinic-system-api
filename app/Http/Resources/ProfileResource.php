<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
    return [
        'phone'       => $this->phone,
        'first_name'  => $this->first_name,
        'last_name'   => $this->last_name,
        'photo'       => $this->photo,
        'gender_id'=>$this->gender_id,
        'address'=>$this->address,
        'is_active'   => $this->is_active,
    ];
    }
}
