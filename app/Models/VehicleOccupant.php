<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleOccupant extends Model
{
    use HasFactory;

    protected $table = 'vehicleoccupants';

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con Usertype
    public function usertype()
    {
        return $this->belongsTo(Usertype::class, 'usertype_id');
    }

    protected $guarded = [];
}
