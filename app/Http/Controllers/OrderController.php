<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'courier_id' => 'required|exists:couriers,id',
            'client_id' => 'required|exists:clients,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'delivery_address' => 'required|string',
            'pickup_address' => 'required|string',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $order = Order::create($validated);

        $courier = User::role('courier')->inRandomOrder()->first();

        if ($courier) {
            $order->courier_id = $courier->id;
            $order->save();

            $courier->notify(new NewOrderAssigned($order));
        }

        return response()->json(['message' => 'Заказ создан и передан курьеру.'], 201);
    }



    public function reassignCourier(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $newCourier = User::role('courier')->where('id', '!=', $order->courier_id)->inRandomOrder()->first();

        if ($newCourier) {
            $order->courier_id = $newCourier->id;
            $order->save();

            $newCourier->notify(new NewOrderAssigned($order));
        }

        return response()->json(['message' => 'Order reassigned to another courier'], 200);
    }

    public function updateStatus(Request $request, Order $order)
    {

        $user = Auth::user();

        $newStatus = $request->input('status');
        switch ($newStatus) {
            case Order::STATUS_NEW:
                $order->updateStatus(Order::STATUS_NEW);
                break;

            case Order::STATUS_PREPARING:
                if ($user->hasRole('restaurant')) {
                    $order->updateStatus(Order::STATUS_PREPARING);
                }
                break;

            case Order::STATUS_READY:
                if ($user->hasRole('restaurant')) {
                    $order->updateStatus(Order::STATUS_READY);
                    // Уведомление курьера о готовности заказа
                    // Здесь можно добавить логику уведомления, если нужно
                }
                break;


            case Order::COURIER_STATUS_ASSIGNED:
                if ($user->hasRole('courier')) {
                    $order->updateStatus(Order::COURIER_STATUS_ASSIGNED);
                }
                break;

            case Order::COURIER_STATUS_WAITING:
                if ($user->hasRole('courier')) {
                    $order->updateStatus(Order::COURIER_STATUS_WAITING);
                }
                break;

            case Order::COURIER_STATUS_PICKED_UP:
                if ($user->hasRole('courier')) {
                    $order->updateStatus(Order::COURIER_STATUS_PICKED_UP);
                    $order->updateStatus(Order::STATUS_ON_THE_WAY);

                }
                break;

            case Order::COURIER_STATUS_ON_THE_WAY:
                if ($user->hasRole('courier')) {
                    $order->updateStatus(Order::COURIER_STATUS_ON_THE_WAY);
                }
                break;

            case Order::COURIER_STATUS_DELIVERED:
                if ($user->hasRole('courier')) {
                    $order->updateStatus(Order::COURIER_STATUS_DELIVERED);
                    $order->updateStatus(Order::STATUS_DELIVERED);
                    notify(Order::STATUS_DELIVERED);

                }
                break;


            case Order::CLIENT_STATUS_PREPARING:
                if ($user->hasRole('client')) {
                    $order->updateStatus(Order::CLIENT_STATUS_PREPARING);
                }
                break;

            case Order::CLIENT_STATUS_ON_THE_WAY:
                if ($user->hasRole('client')) {
                    $order->updateStatus(Order::CLIENT_STATUS_ON_THE_WAY);
                }
                break;

            case Order::CLIENT_STATUS_DELIVERED:
                if ($user->hasRole('client')) {
                    $order->updateStatus(Order::CLIENT_STATUS_DELIVERED);
                }
                break;

            default:
                return response()->json(['error' => 'Некорректный статус'], 400);
        }

        $order->save();

        return response()->json(['status' => 'Статус заказа обновлен']);
    }


}
