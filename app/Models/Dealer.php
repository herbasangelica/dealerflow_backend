<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'dealerName', 'dealerAddr', 'dealerPhone', 'dealerEmail'];

    public function manufacturerVehicles()
    {
        return $this->hasMany(ManufacturerVehicle::class);
    }
}
