<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Courier;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = [
            'couriers' => $this->getCouriersReport(),
            'clients' => $this->getClientsReport(),
            'restaurants' => $this->getRestaurantsReport(),
            'statistics' => $this->getStatistics($startDate, $endDate),
        ];
        return response()->json($data);
    }


    public function getFinancialReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        $filters = $request->input('filters', []);

        // Общие доходы от доставок
        $totalRevenue = 0;
        if (isset($filters['totalRevenue']) && $filters['totalRevenue']) {
            $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->sum('price');
        }

        // Средняя оплата за доставку
        $averagePayment = 0;
        if (isset($filters['averagePayment']) && $filters['averagePayment']) {
            $averagePayment = Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->avg('price');
        }

        // Зарплата курьеров
        $couriersSalary = 0;
        if (isset($filters['couriersSalary']) && $filters['couriersSalary']) {
            $couriersSalary = Payment::whereBetween('payment_date', [$startDate, $endDate])
                ->sum('amount');
        }

        // История выплат курьерам
        $paymentsHistory = [];
        if (isset($filters['paymentsHistory']) && $filters['paymentsHistory']) {
            $paymentsHistory = Payment::whereBetween('payment_date', [$startDate, $endDate])
                ->with('courier')
                ->get();
        }

        return response()->json([
            'total_revenue' => $totalRevenue,
            'average_payment' => $averagePayment,
            'couriers_salary' => $couriersSalary,
            'payments_history' => $paymentsHistory,
        ]);
    }

    public function courierStats()
    {

        $activeCouriers = Courier::where('status', 'active')->count();


        $ordersPerCourier = Order::whereIn('courier_id', Courier::pluck('id'))->count();


        $totalOrders = Order::whereDate('created_at', Carbon::today())->count();
        $ordersPerDay = $totalOrders > 0 ? $totalOrders / Carbon::now()->diffInDays(Carbon::now()->startOfYear()) : 0;


        $averageDeliveryTime = Order::whereNotNull('delivered_at')->avg('delivery_time');


        $totalDelivered = Order::where('status', 'DELIVERED')->count();
        $totalOrdersCount = Order::count();
        $successRate = $totalOrdersCount > 0 ? ($totalDelivered / $totalOrdersCount) * 100 : 0;


        return response()->json([
            'active_couriers' => $activeCouriers,
            'orders_per_courier' => $ordersPerCourier,
            'average_orders_per_day' => round($ordersPerDay, 2),
            'average_delivery_time' => round($averageDeliveryTime, 2), // предполагаем, что delivery_time в минутах
            'success_rate' => round($successRate, 2),
        ]);
    }
}

