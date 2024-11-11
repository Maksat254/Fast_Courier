<?php
namespace App\Http\Controllers;

use App\Models\Order;
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
            'courier_id' => 'required|exists:couriers,id',
            'client_id' => 'required|exists:clients,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'delivery_address' => 'required|string',
            'pickup_address' => 'required|string',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $order = $this->orderService->createOrder($validated);

        $assignedOrder = $this->orderService->assignCourier($order);

        if ($assignedOrder) {
            return response()->json(['message' => 'Заказ создан и передан курьеру.'], 201);
        }

        return response()->json(['message' => 'Нет доступных курьеров для принятия заказа'], 422);
    }

    public function reassignCourier(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->courier_accepted) {
            return response()->json(['message' => 'Заказ уже принят курьером и не может быть переназначен'], 422);
        }

        $assignedOrder = $this->orderService->reassignCourier($order);

        if ($assignedOrder) {
            return response()->json(['message' => 'Заказ переназначен другому курьеру'], 200);
        }

        return response()->json(['message' => 'Нет доступных курьеров для переназначения'], 422);
    }

    public function acceptOrder($orderId)
    {
        $order = Order::findOrFail($orderId);

        $acceptedOrder = $this->orderService->acceptOrder($order);

        if ($acceptedOrder) {
            return response()->json(['message' => 'Заказ принят курьером'], 200);
        }

        return response()->json(['message' => 'Заказ уже принят'], 400);
    }
}
