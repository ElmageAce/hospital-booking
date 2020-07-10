<?php

namespace App\Http\Controllers\Appointments;

use App\Appointments\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSchedulesRequest;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * @param Appointment $appointment
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * @param Appointment $appointment
     */
    public function edit(Appointment $appointment)
    {
        // Make sure doctor edits his own schedule
        if((int)$appointment->doctor_id !== (int)auth()->id())
            return abort(403, "You can't edit another doctor's schedule");

    }

    /**
     * @param UpdateSchedulesRequest $request
     * @param Appointment $appointment
     */
    public function update(UpdateSchedulesRequest $request, Appointment $appointment)
    {
        $data = $request->all();
        // Check doctor's schedule for availability
        if($appointment->checkDoctorSchedule($data['doctor'], $data['date'], $data['id']))
            return abort(403, "The doctor already has an appointment at the selected time");

        $appointment->updateDoctorSchedule($request);
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
