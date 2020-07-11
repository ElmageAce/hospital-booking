<?php

namespace App\Http\Resources;

use App\Http\Resources\Role as RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'role' => new RoleResource($this->whenLoaded('role')),
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar ? storageLink($this->avatar) : '',
            'phone_number' => $this->phone,
            'address' => $this->address,
            'birth_date' => $this->dob,
            'joined' => $this->created_at,
            'joined_formatted' => $this->created_at->toDayDateTimeString(),
            'last_updated' => $this
                ->when(user()->id === $this->id, $this->updated_at),
            'last_updated_formatted' => $this
                ->when(user()->id === $this->id, $this->updated_at->toDayDateTimeString())
        ];

    }
}
