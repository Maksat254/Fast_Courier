<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    const STATUS_NEW = 'Новый заказ';
    const STATUS_PREPARING = 'Готовится';
    const STATUS_READY = 'Готов к выдаче';
    const STATUS_ON_THE_WAY = 'В пути';
    const STATUS_DELIVERED = 'Доставлено';


    const COURIER_STATUS_ASSIGNED = 'Принят';
    const COURIER_STATUS_WAITING = 'Ожидание в ресторане';
    const COURIER_STATUS_PICKED_UP = 'Забрал заказ';
    const COURIER_STATUS_ON_THE_WAY = 'Прибыл к клиенту';
    const COURIER_STATUS_DELIVERED = 'Доставлено курьером';


    const CLIENT_STATUS_PREPARING = 'Ваш заказ готовится';
    const CLIENT_STATUS_ON_THE_WAY = 'В пути';
    const CLIENT_STATUS_DELIVERED = 'Доставлено клиенту';

    protected $fillable = [
        'client_id',
        'restaurant_id',
        'status',
        'delivery_address',
        'pickup_address',
        'total_amount',
    ];

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        $this->save();
    }


}

