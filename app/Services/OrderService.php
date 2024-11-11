<?php


namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderAssigned;

class OrderService
{
    public function createOrder(array $validated)
    {
        $order = Order::create($validated);
        $order->courier_accepted = false;
        $order->save();

        return $order;
    }

    public function assignCourier(Order $order)
    {
        $courier = User::role('courier')->inRandomOrder()->first();

        if ($courier) {
            $order->courier_id = $courier->id;
            $order->save();
            $courier->notify(new NewOrderAssigned($order));

            return $order;
        }

        return null;
    }

    public function reassignCourier(Order $order)
    {
        $newCourier = User::role('courier')->whereDoesntHave('orders', function ($query) {
            $query->where('status', 'В процессе');
        })->where('id', '!=', $order->courier_id)->inRandomOrder()->first();

        if ($newCourier) {
            $order->courier_id = $newCourier->id;
            $order->save();

            $newCourier->notify(new NewOrderAssigned($order));

            return $order;
        }

        return null;
    }

    public function acceptOrder(Order $order)
    {
        if ($order->courier_accepted) {
            return false;
        }

        $order->courier_accepted = true;
        $order->status = 'В процессе';
        $order->save();

        return $order;
    }
}

