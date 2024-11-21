<?php
namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class CourierLocationController extends Controller
{
    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $courierId = $request->user()->id;

        $hasActiveOrder = Order::where('courier_id', $courierId)
            ->whereIn('status', ['Принят', 'Ожидание в ресторане', 'В пути', 'Забрал заказ', 'Прибыл к клиенту'])
            ->exists();

        Redis::hmset("courier:location:{$courierId}", [
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'has_active_order' => $hasActiveOrder,
            'updated_at' => now()->toDateTimeString(),
        ]);

        return response()->json([
            'has_active_order' => $hasActiveOrder,
        ]);
    }

}
