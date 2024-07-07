<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['day_of_week', 'start_time', 'end_time', 'vehicle_id', 'type', 'maintenance_id'];

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
