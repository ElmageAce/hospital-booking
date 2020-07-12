<?php

namespace App\Http\Resources;

use App\Http\Resources\User as UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class Appointment extends JsonResource
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
            'doctor' => new UserResource($this->whenLoaded('doctor')),
            'service' => $this->title,
            'status' => $this->status,
            'date' => Carbon::parse($this->date)->toFormattedDateString(),
            'date_string' => $this->date,
            'start_time' => Carbon::parse($this->date)->format('h:ia'),
            'stop_time' => Carbon::parse($this->date)->addHours((int)$this->period)->format('h:ia'),
            'desc' => $this->desc
        ];
    }
}
