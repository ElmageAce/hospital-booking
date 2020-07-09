<?php

namespace App\Appointments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

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

    public function updateAppointment(FormRequest $request): bool
    {
        $data = $request->all();
        return $this->where('id', $request->input('id'))->update([
            'patient_id' => $data['patient'],
            'doctor_id' => $data['doctor'],
            'title' => $data['title'],
            'desc' => $data['desc'] ?? null,
            'date' => $data['date'],
            'status' => $data['status']
        ]);
    }

    public function checkDoctorSchedule(FormRequest $request, int $period = 2): bool
    {
        // Get datetime carbon instance
        $carbon_date = Carbon::parse($request->input('date'));
        // GET date
        $date = $carbon_date->format('Y-m-d');
        // GET appointment time
        $start_time = $carbon_date->format('H:i:s');
        // GET appointment end time
        $end_time = $carbon_date->addHours($period)->format('H:i:s');
        // Check if time period is occupied
        $query = $this->where('doctor_id', $request->input('doctor'))
            ->whereDate('date', $date)
            ->whereTime('date', '>=', $start_time)
            ->whereTime('date', '<=', $end_time);
        // If user is creating new appointment, that time period must not exists
        if(!$request->has('id')) return $query->exists();
        // GET appointment for updated user
        $doctor_appointment = $query->first();
        // Return true if the appointment isn't the original time
        return (int) $doctor_appointment->id !== (int) $request->has('id');
    }

    public function getPatientAppointments(int $patient_id): Collection
    {
        return $this->where('patient_id', $patient_id)->latest()->get();
    }

}
