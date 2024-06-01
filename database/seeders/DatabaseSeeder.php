<?php

namespace Database\Seeders;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        //Comandos

        //Cargr solo data nueva con seeders : php artisan migrate --seed
        //Borrar todo y volver a cargarlo con seeders: php artisan migrate:fresh --seed

        //Crear seeder> php artisan make:seeder VehiclecolorSeeder
        //Crear modelo> php artisan make:model Vehicle
        //Crear migracion> php artisan make:migration create_vehicle_table


        $this->call(BrandsSeeder::class);
        $this->call(VehicletypeSeeder::class);
        $this->call(VehiclecolorSeeder::class);
        $this->call(UsertypeSeeder::class);
        $this->call(MasterUserSeeder::class);
        $this->call(UserForAssignmentSeeder::class);
        $this->call(BrandModelSeeder::class);
        // $this->call(ZoneSeeder::class);

    }
}
