<?php
namespace App\Services;

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Courier;

class OrderService
{
    protected $courierService;

    public function __construct(CourierService $courierService)
    {
        $this->courierService = $courierService;
    }

    public function createOrder($validated)
    {
        session(['order_status' => $validated['status']]);

        $restaurant = Restaurant::find($validated['restaurant_id']);
        $restaurantLat = $restaurant->latitude;
        $restaurantLon = $restaurant->longitude;


        $order = new Order();
        $order->client_id = $validated['client_id'];
        $order->restaurant_id = $validated['restaurant_id'];
        $order->delivery_address = $validated['delivery_address'];
        $order->pickup_address = $validated['pickup_address'];
        $order->total_amount = $validated['total_amount'];
        $order->description = $validated['description'] ?? '';
        $order->courier_accepted = false;

        $order->status = session('order_status');

        $order->save();



        $courier = $this->courierService->findNearestCourier($restaurantLat, $restaurantLon);

        if ($courier) {
            $order->courier_id = $courier->id;
            $order->save();

            // $courier->notify(new \App\Notifications\NewOrderAssigned($order));
            return $order;
        }

        return null;
    }
}
