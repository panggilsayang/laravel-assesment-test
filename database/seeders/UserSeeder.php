<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin 1',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN
        ]);
        User::create([
            'name' => 'Employee 1',
            'email' => 'employee@test.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_EMPLOYEE
        ]);
        User::create([
            'name' => 'Supervisor 1',
            'email' => 'supervisor@test.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_SUPERVISOR
        ]);
    }
}
