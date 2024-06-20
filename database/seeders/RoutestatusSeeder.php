<?php

namespace Database\Seeders;

use App\Models\Routestatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoutestatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rs1 = new Routestatus();
        $rs1->name = "Programado";
        $rs1->save();

        $rs2 = new Routestatus();
        $rs2->name = "En curso";
        $rs2->save();

        $rs3 = new Routestatus();
        $rs3->name = "Completada";
        $rs3->save();

        $rs4 = new Routestatus();
        $rs4->name = "Cancelada";
        $rs4->save();
    }
}
