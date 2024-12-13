<?php
namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Jobs\AssignCourierJob;
use App\Models\Order;
use App\Models\Client;
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


    public function index()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'delivery_address' => 'required|string',
            'total_amount' => 'required|numeric',
            'status' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!in_array($value, OrderStatus::all())) {
                    $fail('The ' . $attribute . ' is not a valid status.');
                }
            }],
            'description' => 'nullable|string',
        ]);


        $order = $this->orderService->createOrder($validated);

        if ($order) {
            AssignCourierJob::dispatch($order);

            return response()->json(['message' => 'Заказ создан. Курьер будет назначен через некоторое время.'], 201);
        }

        return response()->json(['message' => 'Нет доступных курьеров для назначения.'], 422);
    }


    public function getReportTable(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!$startDate || !$endDate) {
            return response()->json(['error' => 'Start date and end date are required'], 422);
        }

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->with('client')
            ->paginate(30);
        $tableData = $orders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'client_name' => $order->client->name ?? 'Не указан',
                'status' => $order->status,
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'completed_at' => $order->completed_at ? $order->completed_at->format('Y-m-d H:i:s') : 'Не завершено',
                'total' => $order->total ?? 0,
            ];
        });

        return response()->json([
            'columns' => [
                ['key' => 'order_id', 'label' => 'ID Заказа'],
                ['key' => 'client_name', 'label' => 'Клиент'],
                ['key' => 'status', 'label' => 'Статус'],
                ['key' => 'created_at', 'label' => 'Дата создания'],
                ['key' => 'completed_at', 'label' => 'Дата завершения'],
                ['key' => 'total', 'label' => 'Сумма'],
            ],
            'data' => $tableData,
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'total_pages' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ]
        ]);
    }


}


