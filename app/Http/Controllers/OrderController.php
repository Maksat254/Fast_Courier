<?php
namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'delivery_address' => 'required|string',
            'pickup_address' => 'required|string',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $restaurant = Restaurant::find($validated['restaurant_id']);
        $restaurantLat = $restaurant->latitude;
        $restaurantLon = $restaurant->longitude;

        $order = new Order();
        $order->client_id = $validated['client_id'];
        $order->restaurant_id = $validated['restaurant_id'];
        $order->delivery_address = $validated['delivery_address'];
        $order->pickup_address = $validated['pickup_address'];
        $order->total_amount = $validated['total_amount'];
        $order->status = $validated['status'];
        $order->description = $validated['description'] ?? '';
        $order->courier_accepted = false;
        $order->save();

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
        }

        if ($courier) {
            $order->courier_id = $courier->id;
            $order->save();

//            $courier->notify(new \App\Notifications\NewOrderAssigned($order));

            return response()->json(['message' => 'Заказ создан и передан курьеру.'], 201);
        }

        return response()->json(['message' => 'Нет доступных курьеров для назначения.'], 422);

    }

}
/**
 * Функция для расчёта расстояния между двумя точками.
 *
 * @param float $lat1
 * @param float $lon1
 * @param float $lat2
 * @param float $lon2
 * @return float
 */
function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371;

    $latDelta = deg2rad($lat2 - $lat1);
    $lonDelta = deg2rad($lon2 - $lon1);

    $a = sin($latDelta / 2) * sin($latDelta / 2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($lonDelta / 2) * sin($lonDelta / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
}
