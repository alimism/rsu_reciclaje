<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $ut1 = new Zone();
        $ut1->name = 'Zona Cix';
        $ut1->save();

        $ut2 = new Zone();
        $ut2->name = 'Zona JLO';
        $ut2->save();
    }
}
