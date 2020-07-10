<?php

namespace Tests\Feature;

use App\Appointments\Appointment;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DoctorsScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function runArtisanCommands()
    {
        $this->artisan("db:seed");
        $this->artisan("cache:clear");
    }

    /** @test */
    public function only_logged_in_users_can_view_schedules()
    {
        $this->get('/schedules')
            ->assertRedirect('/login');
    }
    /** @test */
    public function only_logged_in_doctors_can_view_schedules()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());
        $this->get('/schedules')->assertStatus(403);
    }
    /** @test */
    public function auth_doctors_can_view_schedules()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('doctor')->create());
        $this->get('/schedules')->assertOk();
    }


    /** @test */
    public function only_logged_in_users_can_view_schedule_details()
    {
        $this->get('/schedules/show/' . 1)
            ->assertRedirect('/login');
    }
    /** @test */
    public function only_logged_in_doctors_can_view_schedule_details()
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

        $this->get('/schedules/show/' . $appointment->id)
            ->assertStatus(403);
    }
    /** @test */
    public function auth_doctors_can_view_schedule_details()
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
        $this->actingAs(factory(User::class)->state('doctor')->create());
        $this->get('/schedules/show/'. $appointment->id)->assertOk();
    }


    /** @test */
    public function only_logged_in_users_can_edit_schedule()
    {
        $this->get('/schedules/edit/' . 1)
            ->assertRedirect('/login');
    }
    /** @test */
    public function only_logged_in_doctors_can_edit_schedule()
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

        $this->get('/schedules/edit/' . $appointment->id)
            ->assertStatus(403);
    }
    /** @test */
    public function auth_doctors_can_edit_schedules()
    {
        $this->runArtisanCommands();
        $patient = factory(User::class)->states('patient')->create();
        $doctor = factory(User::class)->states('doctor')->create();

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'title' => 'Title of appointment',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);
        $this->actingAs($doctor);
        $this->get('/schedules/edit/'. $appointment->id)->assertOk();
    }
    /** @test */
    public function doctors_can_only_edit_their_schedules()
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

        $this->actingAs(factory(User::class)->state('doctor')->create());
        $this->get('/schedules/edit/'. $appointment->id)->assertStatus(403);
    }


    /** @test */
    public function only_logged_in_users_can_update_schedule()
    {
        $response = $this->patch('/schedules/update', [
            'id' => 1,
            'date' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'status' => 'completed'
        ]);

        $response->assertRedirect('/login');
    }
    /** @test */
    public function only_logged_in_doctors_can_update_schedule()
    {
        $this->runArtisanCommands();
        $patient = factory(User::class)->states('patient')->create();
        $this->actingAs($patient);


        $response = $this->patch('/schedules/update', [
            'id' => 1,
            'date' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'status' => 'completed'
        ]);

        $response->assertStatus(403);
    }
    /** @test */
    public function all_fields_required_to_update_schedule()
    {
        $this->runArtisanCommands();
        $doctor = factory(User::class)->states('doctor')->create();
        $this->actingAs($doctor);

        $response = $this->patch('/schedules/update', [
            'id' => null,
            'date' => '',
            'doctor' => $doctor->id,
            'status' => ''
        ]);

        $response->assertSessionDoesntHaveErrors(['doctor']);
        $response->assertSessionHasErrors(['id', 'date', 'status']);
    }
    /** @test */
    public function id_field_must_exists_in_appointments_db_to_update_schedule()
    {
        $this->runArtisanCommands();
        $doctor = factory(User::class)->states('doctor')->create();
        $this->actingAs($doctor);

        $response = $this->patch('/schedules/update', [
            'id' => 1,
            'doctor' => $doctor->id,
            'date' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        $response->assertSessionHasErrors(['id']);
        $response->assertSessionDoesntHaveErrors(['date', 'status', 'doctor']);
    }
    /** @test */
    public function date_field_must_be_a_valid_date_string_to_update_schedule()
    {
        $this->runArtisanCommands();
        $doctor = factory(User::class)->states('doctor')->create();
        $this->actingAs($doctor);

        $response = $this->patch('/schedules/update', [
            'id' => 1,
            'doctor' => $doctor->id,
            'date' => 'A none date-string',
            'status' => 'pending'
        ]);

        $response->assertSessionHasErrors(['date']);
    }
    /** @test */
    public function doctors_can_only_update_their_schedules()
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

        $this->actingAs(factory(User::class)->state('doctor')->create());

        $this->patch('/schedules/update', [
            'id' => $appointment->id,
            'date' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'status' => 'completed'
        ])->assertStatus(403);
    }
    /** @test */
    public function auth_doctors_can_update_schedule()
    {
        $this->runArtisanCommands();
        $patient = factory(User::class)->states('patient')->create();
        $doctor = factory(User::class)->states('doctor')->create();
        $this->actingAs($doctor);

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'title' => 'Title of appointment',
            'desc' => 'Description of reasons',
            'date' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        $response = $this->patch('/schedules/update', [
            'id' => $appointment->id,
            'doctor' => $doctor->id,
            'date' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'status' => 'completed'
        ]);

        $response->assertSessionDoesntHaveErrors(['id', 'date', 'status', 'doctor']);
        $this->assertEquals('completed', Appointment::first()->status);
    }
}
