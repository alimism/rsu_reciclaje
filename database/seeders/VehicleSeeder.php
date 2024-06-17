<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\Vehicleimage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $v1 = new Vehicle();
        $v1->name = 'Vehiculo 1';
        $v1->code = 'V001';
        $v1->plate = '123ABC';
        $v1->year = '2020';
        $v1->capacity = 4;
        $v1->status = 1;
        $v1->brand_id = 1;
        $v1->model_id = 1;
        $v1->type_id = 1;
        $v1->color_id = 1;
        $v1->save();

        // Crear una imagen asociada al vehículo
        $img1 = new Vehicleimage();
        $img1->vehicle_id = $v1->id;
        $img1->profile=1;
        $img1->image = '/storage/vehicles_images/rEE7vGZ7xq8hSJqsUelM6SLYVk0FlIVpgbQbofqJ.jpg'; // Ajusta este valor a la ruta de tu imagen
        $img1->save();
    }
}
