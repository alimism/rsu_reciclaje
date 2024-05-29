<?php

namespace Database\Seeders;

use App\Models\BrandModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mercedes Benz Models
        BrandModel::create([
            'name' => 'Actros 2541',
            'brand_id' => 1, // Mercedes Benz
        ]);
        BrandModel::create([
            'name' => 'Actros 3344',
            'brand_id' => 1, // Mercedes Benz
        ]);

        // Mitsubishi Models
        BrandModel::create([
            'name' => 'Fuso Fighter',
            'brand_id' => 2, // Mitsubishi
        ]);
        BrandModel::create([
            'name' => 'Fuso Canter',
            'brand_id' => 2, // Mitsubishi
        ]);

        // Volvo Models
        BrandModel::create([
            'name' => 'FE 320',
            'brand_id' => 3, // Volvo
        ]);
        BrandModel::create([
            'name' => 'FMX 440',
            'brand_id' => 3, // Volvo
        ]);

        // Scania Models
        BrandModel::create([
            'name' => 'P310',
            'brand_id' => 4, // Scania
        ]);
        BrandModel::create([
            'name' => 'P360',
            'brand_id' => 4, // Scania
        ]);
    }
}
