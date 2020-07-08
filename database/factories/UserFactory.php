<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'dob' => $faker->date(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->state(User::class, 'patient', function(){
    $role = \App\Role::where('slug', 'patient')->first();
    return [
        'role_id' => $role->id
    ];
});

$factory->state(User::class, 'doctor', function(){
    $role = \App\Role::where('slug', 'doctor')->first();
    return [
        'role_id' => $role->id
    ];
});
