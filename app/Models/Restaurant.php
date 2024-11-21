<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'email',
        'latitude',
        'longitude',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
