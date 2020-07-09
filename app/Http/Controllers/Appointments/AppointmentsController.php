<?php

namespace App\Http\Controllers\Appointments;

use App\Appointments\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    /**
     * @param Appointment $appointment
     * @return JsonResponse
     */
    public function index(Appointment $appointment): JsonResponse
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param CreateAppointmentRequest $request
     * @param Appointment $appointment
     * @return JsonResponse
     */
    public function store(CreateAppointmentRequest $request, Appointment $appointment): JsonResponse
    {
        // Check doctor's schedule for availability
        if($appointment->checkDoctorSchedule($request))
            return abort(403, "The doctor already has an appointment at the selected time");
        // Book Appointment
        $appointment->bookAppointment($request);
        // Notify doctor of appointment

        //return response
        return response()->json([
            'status' => 'success',
            'message' => "Appointment booked successfully!"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param UpdateAppointmentRequest $request
     * @param Appointment $appointment
     * @return JsonResponse
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment): JsonResponse
    {
        // Check doctor's schedule for availability
        if($appointment->checkDoctorSchedule($request))
            return abort(403, "The doctor already has an appointment at the selected time");
        // Update appointment
        $status = $appointment->updateAppointment($request);

        return $status
            ?
            response()->json([
                'status' => 'success',
                'message' => 'Appointment changed!'
            ])
            :
            response()->json([
                'status' => 'error',
                'message' => 'Error updating appointment'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
