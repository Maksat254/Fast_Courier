<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password')
            ]
        );

        // Назначаем роль
        $user->assignRole($adminRole);
    }
}
