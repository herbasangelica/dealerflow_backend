<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;


    protected $fillable = [
        'customerName',
        'customerAddr',
        'customerPhone',
        'customerGender',
        'customerAnnualIncome',
    ];

    public function Sale()
    {
        return $this->hasMany(Sale::class);
    }
}
