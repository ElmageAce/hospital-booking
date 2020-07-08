<?php

namespace App\Appointments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class Appointment extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function bookAppointment(FormRequest $request): Appointment
    {
        $data = $request->all();
        return $this->create([
            'patient_id' => $data['patient'],
            'doctor_id' => $data['doctor'],
            'title' => $data['title'],
            'desc' => $data['desc'] ?? null,
            'date' => $data['date'],
            'status' => 'pending'
        ]);
    }
}
