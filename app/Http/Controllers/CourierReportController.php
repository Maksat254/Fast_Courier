<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class CourierReportController extends Controller
{
    public function dailyReport(Request $request)
    {
        $courierId = auth()->id();
        $today = Carbon::today();


        $totalOrders = Order::where('courier_id', $courierId)
            ->whereDate('created_at', $today)
            ->count();

        $totalEarnings = Order::where('courier_id', $courierId)
            ->whereDate('created_at', $today)
            ->sum('price');

        $workLogs = CourierWorkLog::where('courier_id', $courierId)
            ->whereDate('start_time', $today)
            ->get();

        $totalHoursWorked = $workLogs->sum(function ($log) {
            return $log->end_time->diffInHours($log->start_time);
        });

        return response()->json([
            'date' => $today->toDateString(),
            'total_orders' => $totalOrders,
            'total_earnings' => $totalEarnings,
            'total_hours_worked' => $totalHoursWorked,
        ]);
    }

    public function orderHistory(Request $request)
    {
        $courierId = auth()->id();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $orders = Order::where('courier_id', $courierId)
            ->when($startDate, function ($query, $startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            })
            ->get(['id', 'address', 'status', 'price', 'created_at']);

        return response()->json([
            'orders' => $orders,
        ]);
    }

}
