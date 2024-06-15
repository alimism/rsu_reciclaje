<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserForAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Conductor (usertype_id = 3)
        User::create([
            'name' => 'Carlos',
            'lastname' => 'Gomez',
            'DNI' => '1234567890',
            'birthdate' => '1985-01-01',
            'email' => 'conductor1@example.com',
            'license' => 'ABC123',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'usertype_id' => 3,
            'status' => 1,
        ]);

        User::create([
            'name' => 'Pedro',
            'lastname' => 'Panfilo',
            'DNI' => '0987654321',
            'birthdate' => '1986-02-02',
            'email' => 'conductor2@example.com',
            'license' => 'DEF456',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'usertype_id' => 3,
            'status' => 1,

        ]);

        // Recolector (usertype_id = 4)
        User::create([
            'name' => 'Maria',
            'lastname' => 'Gonzales',
            'DNI' => '1122334455',
            'birthdate' => '1987-03-03',
            'email' => 'recolector1@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'usertype_id' => 4,
            'status' => 1,

        ]);

        User::create([
            'name' => 'Tulio',
            'lastname' => 'Fernandez',
            'DNI' => '5544332211',
            'birthdate' => '1988-04-04',
            'email' => 'recolector2@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'usertype_id' => 4,
            'status' => 1,
        ]);
    }
}
