<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zonecoord extends Model
{
    use HasFactory;

    protected $fillable = ['latitude', 'longitude','zone_id'];

    public function zone()
    {

        return $this->belongsTo(Zone::class, 'zone_id');
    }
}
