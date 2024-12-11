<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Courier;
use Illuminate\Http\Request;

class FinancialReportController extends Controller
{
    public function getReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!$startDate || !$endDate) {
            return response()->json(['error' => 'Start date and end date are required'], 422);
        }


        $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();


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
        ]);
    }

}
