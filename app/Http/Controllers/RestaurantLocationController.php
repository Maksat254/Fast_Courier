<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantLocationController extends Controller
{
    public function updateLocation(Request $request, $restaurantId)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $restaurant = Restaurant::findOrFail($restaurantId);
        $restaurant->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        return response()->json(['message' => 'Геолокация ресторана обновлена']);
    }
}
