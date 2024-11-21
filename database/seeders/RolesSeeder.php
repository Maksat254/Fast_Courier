<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        // Создаем роли
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'courier', 'guard_name' => 'api']);
        Role::create(['name' => 'client']);

        // Создаем разрешения
        Permission::create(['name' => 'manage-orders', 'guard_name' => 'api']);
    }
}
