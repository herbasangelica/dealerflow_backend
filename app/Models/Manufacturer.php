<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $fillable = [
        'manufacturerName',
        'manufacturerEmail',
        'manufacturerAddr',
        "description",
    ];

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }
    public function manufacturerVehicles()
    {
        return $this->hasMany(ManufacturerVehicle::class);
    }
}
