<?php

namespace Database\Seeders;

use App\Models\Vehiclecolor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehiclecolorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $c1 = new Vehiclecolor();
        $c1->name = 'F0F8FF';
        $c1->save();

        $c2 = new Vehiclecolor();
        $c2->name = 'ED3A3A';
        $c2->save();

        $c3 = new Vehiclecolor();
        $c3->name = '6CC365';
        $c3->save();
    }
}
