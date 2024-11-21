<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

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

        return response()->json(['message' => 'Courier created successfully', 'data' => $courier,], 201);
    }






   public function arrivedAtRestaurant(Order $order)
   {
       if ($order->courier_id !== auth()->user()->id){
           return response()->json(['massage'=>'заказ не']);
       }
   }
}
