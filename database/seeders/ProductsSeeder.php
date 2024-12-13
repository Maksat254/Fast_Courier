<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        $faker = Faker::create();
        $restaurantProducts = [
            1 => [
                ['name' => 'Борщ', 'price' => 120.00, 'description' => 'Классический украинский борщ.'],
                ['name' => 'Пельмени', 'price' => 150.00, 'description' => 'Пельмени со сметаной.'],
            ],
            2 => [
                ['name' => 'Пицца Пепперони', 'price' => 300.00, 'description' => 'Пицца с сыром и пепперони.'],
                ['name' => 'Паста Карбонара', 'price' => 250.00, 'description' => 'Паста с мясом и сливочным соусом.'],
            ],
        ];
        foreach ($restaurantProducts as $restaurantId => $products) {
            foreach ($products as $product) {
                Product::create([
                    'name' => $product['name'],
                    'restaurant_id' => $restaurantId,
                    'price' => $product['price'],
                    'description' => $product['description'],
                    'image_path' => $product['image_path'] ?? 'images/products/default.jpg',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        for ($restaurantId = 1; $restaurantId <= 20; $restaurantId++) {
            for ($i = 0; $i < 5; $i++) {
                Product::create([
                    'name' => $faker->word,
                    'restaurant_id' => $restaurantId,
                    'price' => $faker->randomFloat(2, 50, 500),
                    'description' => $faker->sentence,
                    'image_path' => $faker->imageUrl(640, 480, 'food', true, 'Product'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
