<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierLocationController extends Controller
{
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $courier = Courier::findOrFail($request->user()->id);
        $courier->latitude = $request->latitude;
        $courier->longitude = $request->longitude;
        $courier->save();

        return response()->json(['message' => 'Location updated successfully']);
    }
}
