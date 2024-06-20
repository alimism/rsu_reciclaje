<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routestatus extends Model
{
    use HasFactory;

    protected $table = 'routestatus';  

    protected $fillable = [
        'name',
        'description'
    ];

    public function vehicleRoutes()
    {
        return $this->hasMany(VehicleRoute::class);
    }
}
