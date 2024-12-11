<?php
// database/seeders/CourierSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Courier;
use Faker\Factory as Faker;

class CourierSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 70) as $index) {
            Courier::create([
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'phone' => $faker->phoneNumber,
                'status' => $faker->randomElement(['Новый заказ', 'Ваш заказ готовится', 'Готов к выдаче','В пути','Доставлено', 'Принят','Ожидание в ресторане', 'Забрал заказ' ]),
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'is_active' => $faker->boolean(80),

            ]);
        }
    }
}
