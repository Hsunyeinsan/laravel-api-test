<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'year'         => $this->year,
            'phone'        => $this->phone,
            'email'        => $this->email,
            'township'     => $this->township,
            'state_division'=> $this->state_division,
            'user_id' => $this->user_id,
            'owner' => $this->user,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
