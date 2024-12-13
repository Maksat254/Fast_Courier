<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\OrderSeeder;
use Database\Seeders\RestaurantSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            CourierSeeder::class,
            ClientSeeder::class,
            RestaurantSeeder::class,
            OrderSeeder::class,
            ProductsSeeder::class
        ]);
    }
}
