<?php
namespace App\Jobs;

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Jobs\ActivateCourierJob;
use App\Jobs\AssignCourierJob;
use App\Models\Courier;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Queue\ShouldQueue;
class CourierController extends Controller
{

    public function index()
    {
        return response()->json(Courier::all(), paginate(10));
    }

    public function active()
    {
        $couriers = Courier::active()->get();
        return response()->json($couriers);
    }

    public function createCourier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:couriers',
            'phone' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $courier = Courier::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        $courier->assignRole('courier', 'api');

        return response()->json(['message' => 'Courier created successfully', 'data' => $courier,]);
    }

    public function assignCourier(Request $request)
    {
        $orderId = $request->input('order_id');

        if (!$orderId) {
            return response()->json(['message' => 'Order ID is required']);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found']    );
        }

        AssignCourierJob::dispatch($order);

        return response()->json(['message' => 'Courier assignment job dispatched successfully.']);
    }

    public function getCourierStats()
    {

        $totalOrders = Order::count();


        $firstOrderDate = Order::min('created_at');
        $lastOrderDate = Order::max('created_at');


        $daysBetween = now()->diffInDays($firstOrderDate);


        $averageOrdersPerDay = $totalOrders / ($daysBetween ?: 1);


        $averageDeliveryTime = Order::selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, delivered_at)) as avg_delivery_time')
            ->whereNotNull('delivered_at') // Только заказы, которые были доставлены
            ->first();


        $successfulDeliveries = Order::where('status', OrderStatus::DELIVERED)->count();


        $successRate = $totalOrders > 0 ? ($successfulDeliveries / $totalOrders) * 100 : 0;


        return response()->json([
            'average_orders_per_day' => round($averageOrdersPerDay, 2), // Среднее количество заказов в день
            'average_delivery_time' => round($averageDeliveryTime->avg_delivery_time, 2), // Среднее время доставки
            'success_rate' => round($successRate, 2), // Процент успешных доставок
        ]);
    }

   public function arrivedAtRestaurant(Order $order)
{
    if ($order->courier_id !== auth()->user()->id) {
        return response()->json(['massage' => 'заказ не']);
    }
}


    public function getCouriersWithOrders()
    {
        $couriers = Courier::withCount('orders')
        ->where('is_active', true) // Только активные курьеры
        ->get();

        return response()->json($couriers);
    }



    public function pause($id)
    {
        $courier = Courier::findOrFail($id);
        $courier->status = 'paused';
        $courier->save();

        ActivateCourierJob::dispatch($courier->id)->delay(now()->addMinutes(30));

        return response()->json(['message' => 'Курьер поставлен на паузу']);
    }

    public function endDay($id)
    {
        $courier = Courier::findOrFail($id);
        $courier->status = 'inactive';
        $courier->save();

        return response()->json(['message' => 'Рабочий день завершен']);
    }

}
