<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturerVehicle extends Model
{
    use HasFactory;

    protected $fillable = ['manufacturer_id', 'car_model_id', 'dealer_id', 'vin', 'price'];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function dealerVehicles()
    {
        return $this->hasMany(DealerVehicle::class);
    }
}
