<?php

namespace Database\Seeders;

use App\Models\Usertype;
use Illuminate\Database\Seeder;

class UsertypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ut1 = new Usertype();
        $ut1->name = 'Administrador';
        $ut1->save();

        $ut2 = new Usertype();
        $ut2->name = 'Ciudadano';
        $ut2->save();

        $ut3 = new Usertype();
        $ut3->name = 'Conductor';
        $ut3->save();

        $ut4 = new Usertype();
        $ut4->name = 'Recolector';
        $ut4->save();

        $ut5 = new Usertype();
        $ut5->name = 'Reciclador';
        $ut5->save();
    }
}
