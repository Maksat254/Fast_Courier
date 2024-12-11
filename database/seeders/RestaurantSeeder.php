<?php
namespace Database\Seeders;

use App\Models\Restaurant;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            Restaurant::create([
                'name' => $faker->company,
                'address' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $faker->phoneNumber,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
            ]);
        }
    }
}
