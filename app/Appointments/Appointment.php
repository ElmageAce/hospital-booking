<?php

namespace App\Appointments;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Appointment extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $status = [
        'pending' => 'pending',
        'completed' => 'completed',
        'canceled' => 'canceled',
        'moved' => 'moved'
    ];

    public function getById(int $id): Appointment
    {
        return $this->find($id);
    }

    public function bookAppointment(FormRequest $request): Appointment
    {
        $data = $request->all();
        return $this->create([
            'patient_id' => $data['patient'],
            'doctor_id' => $data['doctor'],
            'title' => $data['title'],
            'desc' => $data['desc'] ?? null,
            'date' => $data['date'],
            'status' => $this->status['pending']
        ]);
    }

    public function updateAppointment(FormRequest $request): bool
    {
        $data = $request->all();
        if(!in_array($data['status'], $this->status))
            return false;
        return $this->where('id', $request->input('id'))->update([
            'patient_id' => $data['patient'],
            'doctor_id' => $data['doctor'],
            'title' => $data['title'],
            'desc' => $data['desc'] ?? null,
            'date' => $data['date'],
            'status' => $data['status']
        ]);
    }

    public function checkDoctorSchedule(int $doctor_id, string $date, int $appointment_id = null, int $period = 2): bool
    {
        // Get datetime carbon instance
        $carbon_date = Carbon::parse($date);
        // GET date
        $date = $carbon_date->format('Y-m-d');
        // GET appointment time
        $start_time = $carbon_date->format('H:i:s');
        // GET appointment end time
        $end_time = $carbon_date->addHours($period)->format('H:i:s');
        // Check if time period is occupied
        $query = $this->where('doctor_id', $doctor_id)
            ->where('status', '!=', $this->status['moved'])
            ->whereDate('date', $date)
            ->whereTime('date', '>=', $start_time)
            ->whereTime('date', '<=', $end_time);
        // If user is creating new appointment, that time period must not exists
        if(!$appointment_id) return $query->exists();
        // GET appointment for updated user
        $doctor_appointment = $query->first();
        // Return true if the appointment isn't the original time
        return (int) $doctor_appointment->id !== (int) $appointment_id;
    }

    public function getPatientAppointments(int $patient_id): Collection
    {
        return $this->where('patient_id', $patient_id)
            ->where('status', '!=', $this->status['moved'])
            ->with('doctor')
            ->latest()->get();
    }

    public function updateDoctorSchedule(FormRequest $request)
    {
        if(!in_array($request->input('status'), $this->status))
            return false;

        $appointment = $this->getById($request->input('id'));

        $appointment->update([
            'status' => $request->input('status'),
            'date' => $request->input('date')
        ]);

        if($request->input('status') === $this->status['moved']){
            $this->create([
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'title' => $appointment->title,
                'desc' => $appointment->desc,
                'status' => $request->input('status'),
                'date' => $request->input('date')
            ]);

        }

        return true;
    }


    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

}
