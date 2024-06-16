<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routezone extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'zone_id'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    
}
