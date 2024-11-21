<?php

namespace App\Services;

use App\Models\Courier;
use App\Helpers\DistanceHelper;

class CourierService
{
    public function findNearestCourier($restaurantLat, $restaurantLon)
    {
        return Courier::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($courier) use ($restaurantLat, $restaurantLon) {
                $courier->distance = DistanceHelper::calculateDistance($restaurantLat, $restaurantLon, $courier->latitude, $courier->longitude);
                return $courier;
            })
            ->sortBy('distance')
            ->first();
    }
}
