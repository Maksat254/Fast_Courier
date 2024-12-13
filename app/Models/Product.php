<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'restaurant_id', 'price', 'description', 'image_path'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
