<?php
namespace Database\Seeders;

use App\Models\Order;
use App\Models\Courier;
use App\Models\Client;
use App\Models\Restaurant;
use App\Enums\OrderStatus;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 1000) as $index) {
            $statusString = $faker->randomElement([
                'Новый заказ',
                'Ваш заказ готовится',
                'Готов к выдаче',
                'В пути',
                'Доставлено',
                'Принят',
                'Ожидание в ресторане',
                'Забрал заказ',
                'Отменено'
            ]);

            $status = OrderStatus::from($statusString);

            Order::create([
                'client_id' => Client::inRandomOrder()->first()->id,
                'restaurant_id' => Restaurant::inRandomOrder()->first()->id,
                'courier_id' => Courier::active()->inRandomOrder()->first()->id,
                'total_amount' => $faker->randomFloat(2, 5, 100),
                'status' => $status->value,  // Теперь передаем значение объекта, а не строку
                'delivery_address' => $faker->address,
                'pickup_address' => $faker->address,
                'created_at' => $faker->dateTimeThisYear,
                'updated_at' => $faker->dateTimeThisYear,
            ]);
        }
    }
}
