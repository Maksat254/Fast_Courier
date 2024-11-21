<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\OrderStatusService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;
    protected $orderStatusService;

    public function __construct(OrderService $orderService, OrderStatusService $orderStatusService)
    {
        $this->orderService = $orderService;
        $this->orderStatusService = $orderStatusService;
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


        $order = $this->orderService->createOrder($validated);

        if ($order) {
            return response()->json(['message' => 'Заказ создан и передан курьеру.'], 201);
        }

        return response()->json(['message' => 'Нет доступных курьеров для назначения.'], 422);
    }




}


