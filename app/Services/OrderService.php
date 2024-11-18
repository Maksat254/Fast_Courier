<?php
namespace App\Services;

use App\Models\Courier;
use App\Models\Order;
use App\Models\Restaurant;

class OrderService
{
    /**
     * Создаёт новый заказ.
     */
    public function createOrder(array $data, Restaurant $restaurant): Order
    {
        $order = new Order();
        $order->client_id = $data['client_id'];
        $order->restaurant_id = $data['restaurant_id'];
        $order->delivery_address = $data['delivery_address'];
        $order->pickup_address = $data['pickup_address'];
        $order->total_amount = $data['total_amount'];
        $order->status = $data['status'];
        $order->description = $data['description'] ?? '';
        $order->courier_accepted = false;
        $order->save();

        return $order;
    }

    /**
     * Назначает ближайшего курьера для заказа.
     */
    public function assignCourierToOrder(Order $order, Restaurant $restaurant): bool
    {
        $restaurantLat = $restaurant->latitude;
        $restaurantLon = $restaurant->longitude;

        $courier = Courier::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($courier) use ($restaurantLat, $restaurantLon) {
                $courier->distance = calculateDistance($restaurantLat, $restaurantLon, $courier->latitude, $courier->longitude);
                return $courier;
            })
            ->sortBy('distance')
            ->first();

        if ($courier) {
            $order->courier_id = $courier->id;
            $order->save();

            // $courier->notify(new \App\Notifications\NewOrderAssigned($order));
            return true;
        }

        return false;
    }
}
