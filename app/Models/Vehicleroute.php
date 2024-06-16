<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicleroute extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_route',
        'description',
        'routestatus_id',
        'route_id',
        'vehicle_id'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function routeStatus()
    {
        return $this->belongsTo(Routestatus::class, 'routestatus_id');
    }
}
