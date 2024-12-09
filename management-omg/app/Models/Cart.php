<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['quantity', 'car_id'];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    
}
