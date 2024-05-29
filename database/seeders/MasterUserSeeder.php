<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Juan',
            'lastname' => 'Perez',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'), 
            'email_verified_at' => now(),
            'usertype_id' => 1, // Asegúrate de que el ID 1 corresponde a 'Administrador' en tu tabla 'usertypes'
        ]);
    }
}
