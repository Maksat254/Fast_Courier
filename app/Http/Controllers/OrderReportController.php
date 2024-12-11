<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderReportController extends Controller
{
    public function getOrders(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Order::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $orders = $query->get();

        return response()->json([
            'total_orders' => $orders->count(),
            'completed_orders' => $orders->where('status', 'completed')->count(),
            'canceled_orders' => $orders->where('status', 'canceled')->count(),
            'average_time' => $orders->average('completion_time'),
            'peak_hours' => $this->calculatePeakHours($orders),
        ]);
    }

    private function calculatePeakHours($orders)
    {
        $hours = $orders->groupBy(function ($order) {
            return $order->created_at->format('H');
        });

        $peakHour = $hours->maxBy(fn($group) => $group->count());

        return $peakHour ? $peakHour->first()->created_at->format('H:00') . ' - ' . ($peakHour->first()->created_at->format('H') + 1) . ':00' : 'N/A';
    }
}
