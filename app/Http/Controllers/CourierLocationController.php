<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CourierLocationController extends Controller
{
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $courierId = $request->user()->id;


        $hasActiveOrder = Order::where('courier_id', $courierId)
            ->whereIn('status', ['Принят', 'Ожидание в ресторане', 'В пути', 'Забрал заказ', 'Прибыл к клиенту',])
            ->exists();


        Redis::hmset("courier:location:{$courierId}", [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'has_active_order' => $hasActiveOrder,
            'updated_at' => now()->toDateTimeString(),
        ]);

        return response()->json([
            'message' => 'Местоположение успешно обновлено',
            'has_active_order' => $hasActiveOrder,
        ]);
    }

}
