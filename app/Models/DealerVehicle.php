<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerVehicle extends Model
{
    use HasFactory;

    protected $fillable = ['manufacturer_vehicle_id', 'status', 'price'];

    public function manufacturerVehicle()
    {
        return $this->belongsTo(ManufacturerVehicle::class);
    }

    public function Sale()
    {
        return $this->hasMany(Sale::class);
    }
}
