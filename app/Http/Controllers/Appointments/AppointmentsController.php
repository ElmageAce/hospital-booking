<?php

namespace App\Http\Controllers\Appointments;

use App\Appointments\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAppointmentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
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
        // Book Appointment
        $new_appointment = $appointment->bookAppointment($request);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
