<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceActivity extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'description', 'maintenance_id', 'schedule_id'];

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class);
    }

    public function schedule()
    {
        return $this->belongsTo(MaintenanceSchedule::class);
    }
}
