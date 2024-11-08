<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'manage orders']);
        Permission::create(['name' => 'assign courier']);
        Permission::create(['name' => 'update order status']);
        Permission::create(['name' => 'view financial data']);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(['manage orders', 'assign courier', 'view financial data']);

        $courier = Role::create(['name' => 'courier']);
        $courier->givePermissionTo(['update order status']);

        $client = Role::create(['name' => 'client']);
    }
}
