<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ClientController extends Controller
{
    public function updateCourierLocation(Request $request, $courierId)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $hasActiveOrder = Order::where('courier_id', $courierId)
            ->whereIn('status', ['Принят', 'Ожидание в ресторане', 'В пути', 'Забрал заказ', 'Прибыл к клиенту'])
            ->exists();

        $locationKey = "courier:location:{$courierId}";
        Redis::hmset($locationKey, [
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'has_active_order' => $hasActiveOrder,
            'updated_at' => now()->toDateTimeString(),
        ]);

        return response()->json([
            'has_active_order' => $hasActiveOrder,
            'location_data' => Redis::hgetall($locationKey),
        ]);
    }
}
