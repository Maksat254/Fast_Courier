<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Courier;
use App\Models\Restaurant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(AdminRoleSeeder::class);
        $this->call(CourierSeeder::class);

        Client::factory()->count(20)->create();
        Courier::factory()->count(20)->create();
        Restaurant::factory()->count(20)->create();

        $courierUser = User::firstOrCreate(
            ['email' => 'courier@example.com'],
            [
                'name' => 'Courier User',
                'password' => bcrypt('courier_password')
            ]
        );
        $courierUser->assignRole('courier');


    }
}
