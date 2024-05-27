<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    // Relación con Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function model()
    {
        return $this->belongsTo(BrandModel::class, 'model_id');
    }

    public function type()
    {
        return $this->belongsTo(Vehicletype::class, 'type_id');
    }

    public function color()
    {
        return $this->belongsTo(Vehiclecolor::class, 'color_id');
    }

    public function vehicleImage()
    {
        return $this->hasMany(Vehicleimage::class);
    }

    protected $guarded = [];
}
