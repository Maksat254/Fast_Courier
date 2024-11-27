<?php
namespace App\Jobs;

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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




   public function arrivedAtRestaurant(Order $order)
   {
       if ($order->courier_id !== auth()->user()->id){
           return response()->json(['massage'=>'заказ не']);
       }
   }
}
