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
    public function only_logged_in_user_can_visit_index_appointments_page()
    {
        $this->get('/appointments')
            ->assertRedirect('/login');
    }
    /** @test */
    public function only_logged_in_user_can_visit_edit_appointments_page()
    {
        $this->get('/appointments/edit/' . 1)
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
    public function auth_patients_can_visit_index_appointments_page()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());
        $this->get('/appointments')
            ->assertOk();
    }
    /** @test */
    public function auth_patients_can_visit_edit_appointments_page()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());
        $this->get('/appointments/edit/' . 1)
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
    public function auth_doctors_cannot_visit_index_appointments_page()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('doctor')->create());

        $this->get('/appointments')
            ->assertStatus(403);
    }
    /** @test */
    public function auth_doctors_cannot_visit_edit_appointments_page()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('doctor')->create());

        $this->get('/appointments/edit/' . 1)
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
    public function edit_appointment_required_fields_are_required()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());

        $response = $this->patch('/appointments/update', [
            'id' => null,
            'patient' => null,
            'doctor' => null,
            'title' => '',
            'desc' => 'Description of reasons',
            'date' => '',
            'status' => '',
        ]);

        $response->assertSessionHasErrors(['id', 'date', 'title', 'patient', 'doctor', 'status']);
    }


    /** @test */
    public function appointment_patient_and_doctor_id_must_exists_in_database()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());

        $response = $this->post('/appointments', [
            'patient' => 5,// using IDs not in database
            'doctor' => 6,// using IDs not in database
            'title' => 'Appointment title',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(3)->format('Y-m-d H:i:s')
        ]);

        $response->assertSessionHasErrors(['patient', 'doctor']);
    }
    /** @test */
    public function edit_appointment_patient_and_doctor_id_must_exists_in_database()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());

        $response = $this->patch('/appointments/update', [
            'id' => 1,
            'patient' => 5,// using IDs not in database
            'doctor' => 6,// using IDs not in database
            'title' => 'Appointment title',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(3)->format('Y-m-d H:i:s')
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
            'date' => now()->addDays(3)->format('Y-m-d H:i:s')
        ]);

        $response->assertSessionDoesntHaveErrors(['patient', 'doctor']);
    }
    /** @test */
    public function edit_appointment_patient_and_doctor_must_be_in_users_table()
    {
        $this->runArtisanCommands();
        $patient = factory(User::class)->state('patient')->create();
        $doctor = factory(User::class)->state('doctor')->create();
        $this->actingAs($patient);

        $response = $this->post('/appointments/update', [
            'id' => 1,
            'patient' => $patient->id,
            'doctor' => $doctor->id,
            'title' => 'Appointment title',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(3)->format('Y-m-d H:i:s')
        ]);

        $response->assertSessionDoesntHaveErrors(['patient', 'doctor']);
    }


    /** @test */
    public function date_field_must_be_a_date_string()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());

        $response = $this->post('/appointments', [
            'patient' => 1,
            'doctor' => 2,
            'title' => 'Appointment title',
            'desc' => 'Description of reasons',
            'date' => 'A non date-string'
        ]);

        $response->assertSessionHasErrors(['date']);
    }
    /** @test */
    public function edit_appointment_date_field_must_be_a_date_string()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());

        $response = $this->patch('/appointments/update', [
            'patient' => 1,
            'doctor' => 2,
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
    public function edit_appointment_patient_and_doctor_cannot_be_the_same()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());

        $response = $this->patch('/appointments/update', [
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
    public function edit_appointment_only_logged_in_users_can_create_appointments()
    {
        $this->runArtisanCommands();

        $response = $this->patch('/appointments/update', [
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
    public function edit_appointment_doctor_cannot_book_appointment()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('doctor')->create());

        $response = $this->patch('/appointments/update', [
            'patient' => 1,
            'doctor' => 2,
            'title' => 'Title here',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(3)->getTimestamp()
        ]);

        $response->assertStatus(403);
    }


    /** @test */
    public function edit_appointment_id_field_must_exists_in_database()
    {
        $this->runArtisanCommands();

        $patient = factory(User::class)->states('patient')->create();
        $doctor = factory(User::class)->states('doctor')->create();
        $this->actingAs($patient);

        $response = $this->patch('/appointments/update', [
            'id' => 1,
            'patient' => $patient->id,
            'doctor' => $doctor->id,
            'title' => 'updated title',
            'desc' => 'updated description',
            'status' => 'pending',
            'date' => now()->addDays(3)->format('Y-m-d H:i:s')
        ]);

        $response->assertSessionHasErrors(['id']);

    }

    /** @test */
    public function appointment_date_must_be_available()
    {
        $this->runArtisanCommands();

        $patient = factory(User::class)->states('patient')->create();
        $doctor = factory(User::class)->states('doctor')->create();
        $this->actingAs($patient);

        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'title' => 'Title of appointment',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        $response = $this->post('/appointments', [
            'patient' => $patient->id,
            'doctor' => $doctor->id,
            'title' => 'Title of appointment',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(3)->format('Y-m-d H:i:s')
        ]);

        $response->assertStatus(403);
    }
    /** @test */
    public function updated_appointment_date_must_be_available()
    {
        $this->runArtisanCommands();

        $patient = factory(User::class)->states('patient')->create();
        $doctor = factory(User::class)->states('doctor')->create();
        $this->actingAs($patient);

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'title' => 'Title of appointment',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'title' => 'Title of appointment',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(4)->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        $response = $this->patch('/appointments/update', [
            'id' => $appointment->id,
            'patient' => $patient->id,
            'doctor' => $doctor->id,
            'title' => 'updated title',
            'desc' => 'updated description',
            'status' => 'pending',
            'date' => now()->addDays(4)->format('Y-m-d H:i:s')
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
    /** @test */
    public function an_appointment_can_be_updated()
    {
        $this->runArtisanCommands();

        $patient = factory(User::class)->states('patient')->create();
        $doctor = factory(User::class)->states('doctor')->create();
        $this->actingAs($patient);

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'title' => 'Title of appointment',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        $response = $this->patch('/appointments/update', [
            'id' => $appointment->id,
            'patient' => $patient->id,
            'doctor' => $doctor->id,
            'title' => 'updated title',
            'desc' => 'updated description',
            'status' => 'pending',
            'date' => now()->addDays(3)->format('Y-m-d H:i:s')
        ]);

        $response->assertOk();
        $response->assertSessionDoesntHaveErrors(['id', 'patient', 'doctor', 'title', 'desc', 'date', 'status']);
        $this->assertEquals('updated description', Appointment::first()->desc);
        $this->assertEquals('updated title', Appointment::first()->title);
    }

}
