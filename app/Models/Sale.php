<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['dealer_vehicle_id', 'customer_id'];

    public function dealerVehicle()
    {
        return $this->belongsTo(DealerVehicle::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeConvertibles($query)
    {
        return $query->whereHas('dealerVehicle.manufacturerVehicle.carModel', function ($q) {
            $q->where('style', 'Convertible');
        });
    }

    public function scopeByMonth($query, $month)
    {
        return $query->whereMonth('created_at', $month);
    }
}
