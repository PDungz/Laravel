<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'manufacturer', 'color', 'price', 'yearOfManufacture', 'category_id', 'quantity'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
