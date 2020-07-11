<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    public function runArtisanCommands()
    {
        $this->artisan("db:seed");
        $this->artisan("cache:clear");
    }


    /** @test */
    public function only_logged_in_user_can_visit_a_profile()
    {
        $this->get('/profile/' . 1)->assertRedirect('/login');
    }
    /** @test */
    public function auth_user_cannot_visit_a_non_existent_profile()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());
        $this->get('/profile/' . 10)->assertStatus(404);
    }
    /** @test */
    public function auth_patients_cannot_view_another_patient_profile()
    {
        $this->runArtisanCommands();
        $this->actingAs(factory(User::class)->state('patient')->create());
        $patient = factory(User::class)->state('patient')->create();
        $this->get('/profile/' . $patient->id)->assertStatus(403);
    }
    /** @test */
    public function auth_patient_can_view_his_profile()
    {
        $this->runArtisanCommands();
        $patient = factory(User::class)->state('patient')->create();
        $this->actingAs($patient);
        $this->get('/profile/' . $patient->id)->assertStatus(200);
    }
    /** @test */
    public function auth_doctors_can_view_patient_profile()
    {
        $this->runArtisanCommands();
        $patient = factory(User::class)->state('patient')->create();
        $this->actingAs(factory(User::class)->state('doctor')->create());
        $this->get('/profile/' . $patient->id)->assertStatus(200);
    }
    /** @test */
    public function auth_patient_can_view_doctor_profile()
    {
        $this->runArtisanCommands();
        $doctor = factory(User::class)->state('doctor')->create();
        $this->actingAs(factory(User::class)->state('patient')->create());
        $this->get('/profile/' . $doctor->id)->assertStatus(200);
    }


    /** @test */
    public function only_logged_in_user_can_edit_profile()
    {
        $this->runArtisanCommands();
        $patient = factory(User::class)->state('patient')->create();
        $this->get('/profile/' . $patient->id)->assertRedirect('/login');
    }
    /** @test */
    public function auth_user_cannot_edit_another_users_profile()
    {
        $this->runArtisanCommands();
        $doctor = factory(User::class)->state('doctor')->create();
        $patient = factory(User::class)->state('patient')->create();

        $this->actingAs($patient);
        $this->get('/profile/edit/' . $doctor->id)->assertStatus(403);

        $this->actingAs($doctor);
        $this->get('/profile/edit/' . $patient->id)->assertStatus(403);
    }

    /** @test */
    public function only_logged_in_user_can_update_profile()
    {
        $this->runArtisanCommands();
        $this->patch('/profile/update')->assertRedirect('/login');
    }
    /** @test */
    public function required_fields_must_be_provided_to_update_profile()
    {
        $this->runArtisanCommands();
        $patient = factory(User::class)->state('patient')->create();
        $this->actingAs($patient);
        $response = $this->patch('/profile/update', [
            'id' => null,
            'name' => '',
            'avatar' => '',
            'address' => '',
            'phone' => '',
            'dob' => '',
        ]);

        $response->assertSessionDoesntHaveErrors(['avatar']);
        $response->assertSessionHasErrors(['id', 'name', 'address', 'phone', 'dob']);
    }
    /** @test */
    public function profile_id_must_exist_in_users_table()
    {
        $this->runArtisanCommands();
        $patient = factory(User::class)->state('patient')->create();
        $this->actingAs($patient);
        $response = $this->patch('/profile/update', [
            'id' => 5,
            'name' => $this->faker->name,
            'avatar' => '',
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'dob' => $this->faker->date('Y-m-d'),
        ]);

        $response->assertSessionDoesntHaveErrors(['avatar', 'name', 'address', 'phone', 'dob']);
        $response->assertSessionHasErrors(['id']);
    }
    /** @test */
    public function date_field_must_be_a_valid_date_string()
    {
        $this->runArtisanCommands();
        $patient = factory(User::class)->state('patient')->create();
        $this->actingAs($patient);
        $response = $this->patch('/profile/update', [
            'id' => $patient->id,
            'name' => $this->faker->name,
            'avatar' => '',
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'dob' => $this->faker->slug,
        ]);

        $response->assertSessionDoesntHaveErrors(['id', 'name', 'address', 'phone', 'avatar']);
        $response->assertSessionHasErrors(['dob']);
    }
    /** @test */
    public function auth_user_cannot_update_another_users_profile()
    {
        $this->runArtisanCommands();

        $patient = factory(User::class)->state('patient')->create();
        $this->actingAs(factory(User::class)->state('patient')->create());

        $response = $this->patch('/profile/update', [
            'id' => $patient->id,
            'name' => $this->faker->name,
            'avatar' => '',
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'dob' => $this->faker->date('Y-m-d'),
        ]);

        $response->assertStatus(403);
    }
    /** @test */
    public function auth_user_can_update_his_profile()
    {
        $this->runArtisanCommands();

        $patient = factory(User::class)->state('patient')->create();
        $this->actingAs($patient);

        $newPhone = $this->faker->phoneNumber;
        $response = $this->patch('/profile/update', [
            'id' => $patient->id,
            'name' => $this->faker->name,
            'avatar' => '',
            'address' => $this->faker->address,
            'phone' => $newPhone,
            'dob' => $this->faker->date('Y-m-d'),
        ]);

        $response->assertOk();
        $this->assertEquals($newPhone, User::first()->phone);
        $response->assertJson(['status' => 'success']);

    }

}
