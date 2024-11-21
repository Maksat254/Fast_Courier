<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;




class Order extends Model
{
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

    /**
     * Устанавливает следующий статус заказа.
     *
     * @param OrderStatus $newStatus
     * @return void
     */
    public function setStatus(OrderStatus $newStatus): void
    {
        $this->status = $newStatus->value;
        $this->save();
    }

    /**
     * Проверяет текущий статус.
     *
     * @param OrderStatus $status
     * @return bool
     */
    public function isStatus(OrderStatus $status): bool
    {
        return $this->status === $status->value;
    }


}

