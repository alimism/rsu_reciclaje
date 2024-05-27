<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandModel extends Model
{
    protected $table = 'brandmodels';  
    protected $fillable = ['name', 'code', 'description', 'brand_id'];

    use HasFactory;

    // Relación con Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
