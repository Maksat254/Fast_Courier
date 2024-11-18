<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{

    public function index()
    {
        $couriers = Courier::all();
        return response()->json(['couriers' => $couriers]);
    }

    public function create()
    {
        return view('admin.couriers.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:couriers,email',
            'phone' => 'nullable|string|max:20',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $courier = Courier::create($request->all());

        return response()->json(['message' => 'Курьер успешно добавлен', 'courier' => $courier,], 201);    }
}
