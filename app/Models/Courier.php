<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Courier extends Model
{

    use HasRoles,Notifiable, Authenticatable;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'latitude',
        'longitude',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
