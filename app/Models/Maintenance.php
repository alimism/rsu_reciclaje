<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_date', 'end_date'];

    public function schedules()
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }

    public function activities()
    {
        return $this->hasMany(MaintenanceActivity::class);
    }

}
