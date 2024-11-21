<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Order;
use Illuminate\Http\Request;

class CourierController extends Controller
{
   public function arrivedAtRestaurant(Order $order)
   {
       if ($order->courier_id !== auth()->user()->id){
           return response()->json(['massage'=>'заказ не']);
       }
   }
}
