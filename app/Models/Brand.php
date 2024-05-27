<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded=[];

    public function brandModels()
    {
        return $this->hasMany(BrandModel::class);
    }
}
