<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'area',
        'description',
    ];

    public function coords()
    {
        return $this->hasMany(Zonecoord::class, 'zone_id');
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'routezones', 'zone_id', 'route_id');
    }
}
