<?php

namespace Tests\Feature;

use App\Appointments\Appointment;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentBookingTest extends TestCase
{
    use RefreshDatabase;

    public function runArtisanCommands()
    {
        $this->artisan("db:seed");
        $this->artisan("cache:clear");
    }

    /** @test */
    public function only_logged_in_user_can_visit_create_appointments_page()
    {
        $this->get('/appointments/create')
            ->assertRedirect('/login');
    }
    
    /** @test */
    public function auth_patients_can_visit_create_appointments_page()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());
        $this->get('/appointments/create')
            ->assertOk();
    }

    /** @test */
    public function auth_doctors_cannot_visit_create_appointments_page()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('doctor')->create());

        $this->get('/appointments/create')
            ->assertStatus(403);
    }

    /** @test */
    public function appointment_required_fields_are_required()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());

        $response = $this->post('/appointments', [
            'patient' => null,
            'doctor' => null,
            'title' => '',
            'desc' => 'Description of reasons',
            'date' => ''
        ]);

        $response->assertSessionHasErrors(['date', 'title', 'patient', 'doctor']);
    }

    /** @test */
    public function patient_and_doctor_id_must_exists_in_database()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());

        $response = $this->post('/appointments', [
            'patient' => 5,// using IDs not in database
            'doctor' => 6,// using IDs not in database
            'title' => 'Appointment title',
            'desc' => 'Description of reasons',
            'date' => now()->addDays()->getTimestamp()
        ]);

        $response->assertSessionHasErrors(['patient', 'doctor']);
    }

    /** @test */
    public function patient_and_doctor_must_be_in_users_table()
    {
        $this->runArtisanCommands();
        $patient = factory(User::class)->state('patient')->create();
        $doctor = factory(User::class)->state('doctor')->create();
        $this->actingAs($patient);

        $response = $this->post('/appointments', [
            'patient' => $patient->id,
            'doctor' => $doctor->id,
            'title' => 'Appointment title',
            'desc' => 'Description of reasons',
            'date' => now()->addDays()->getTimestamp()
        ]);

        $response->assertSessionDoesntHaveErrors(['patient', 'doctor']);
    }

    /** @test */
    public function date_field_must_be_a_date_string()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());

        $response = $this->post('/appointments', [
            'patient' => 1,// using IDs not in database
            'doctor' => 2,// using IDs not in database
            'title' => 'Appointment title',
            'desc' => 'Description of reasons',
            'date' => 'A non date-string'
        ]);

        $response->assertSessionHasErrors(['date']);
    }

    /** @test */
    public function patient_and_doctor_cannot_be_the_same()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());

        $response = $this->post('/appointments', [
            'patient' => 1,// using same IDs
            'doctor' => 1,// using same IDs
            'title' => 'Appointment title',
            'desc' => 'Description of reasons',
            'date' => 'A non date-string'
        ]);

        $response->assertSessionHasErrors(['patient', 'doctor']);
    }

    /** @test */
    public function only_logged_in_users_can_create_appointments()
    {
        $this->runArtisanCommands();

        $response = $this->post('/appointments', [
            'patient' => 1,
            'doctor' => 2,
            'title' => 'Title here',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(3)->getTimestamp()
        ]);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function doctor_cannot_book_appointment()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('doctor')->create());

        $response = $this->post('/appointments', [
            'patient' => 1,
            'doctor' => 2,
            'title' => 'Title here',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(3)->getTimestamp()
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function an_appointment_can_be_booked()
    {
        $this->runArtisanCommands();
        //$this->withoutExceptionHandling();

        $patient = factory(User::class)->states('patient')->create();
        $doctor = factory(User::class)->states('doctor')->create();
        $this->actingAs($patient);

        $response = $this->post('/appointments', [
            'patient' => $patient->id,
            'doctor' => $doctor->id,
            'title' => 'Title of appointment',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(3)->format('Y-m-d H:i:s')
        ]);

        $response->assertOk();
        $response->assertSessionDoesntHaveErrors(['patient', 'doctor', 'title', 'desc', 'date']);
        $this->assertCount(1, Appointment::all());
    }
}
