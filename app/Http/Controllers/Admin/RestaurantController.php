<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:restaurants,email',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $restaurant = Restaurant::create($request->all());

        return response()->json(['message' => 'Ресторан успешно добавлен', 'restaurant' => $restaurant,], 201);    }
}
