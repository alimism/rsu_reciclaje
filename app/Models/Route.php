<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude_start',
        'longitude_start',
        'latitude_end',
        'longitude_end',
        'status'
    ];

    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'routezones', 'route_id', 'zone_id');
    }

    public function vehicleRoutes()
    {
        return $this->hasMany(VehicleRoute::class);
    }
}
