<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $courierRole = Role::firstOrCreate(['name' => 'courier']);

        for ($i = 1; $i <= 5; $i++) {
            $courier = User::firstOrCreate(
                ['email' => "courier{$i}@example.com"],
                [
                    'name' => "Courier {$i}",
                    'password' => bcrypt('password')
                ]
            );
            $courier->assignRole($courierRole);
        }
    }
}
