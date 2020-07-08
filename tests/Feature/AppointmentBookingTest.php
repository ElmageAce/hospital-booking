<?php

namespace Tests\Feature;

use App\Appointments\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentBookingTest extends TestCase
{
    /** @test */
    public function an_appointment_can_be_booked()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/appointments', [
            'patient_id' => 1,
            'doctor_id' => 2,
            'title' => 'Checkup',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(3)->getTimestamp()
        ]);

        $response->assertOk();
        $this->assertCount(1, Appointment::all());
    }
}
