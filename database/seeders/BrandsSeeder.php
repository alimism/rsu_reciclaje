<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $b1 = new Brand();
        $b1->name = "Mercedes Benz";
        $b1->save();

        $b2 = new Brand();
        $b2->name = "Mitsubichi";
        $b2->save();

        $b3 = new Brand();
        $b3->name = "Volvo";
        $b3->save();

        $b4 = new Brand();
        $b4->name = "Scania";
        $b4->save();
    }
}
