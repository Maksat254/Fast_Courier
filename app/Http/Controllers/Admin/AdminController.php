<?php
namespace App\Http\Controllers\Admin;

use App\Models\Client;
use App\Models\Courier;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function Client()
    {
        return response()->json(Client::all());
    }



    public function Order()
    {
        return response()->json(Order::all());
    }

   
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
            'message' => 'Местоположение курьера успешно обновлено администратором.',
            'has_active_order' => $hasActiveOrder,
            'location_data' => Redis::hgetall($locationKey),
        ]);
    }

}
