<?php

use App\Role;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Patient', 'Doctor'];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'slug' => Str::slug($role)
            ],[
                'name' => $role,
                'slug' => Str::slug($role)
            ]);
        }
    }
}
