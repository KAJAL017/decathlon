<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define modules and their permissions
        $modules = [
            'orders' => ['View Orders', 'Create Orders', 'Edit Orders', 'Delete Orders'],
            'products' => ['View Products', 'Create Products', 'Edit Products', 'Delete Products'],
            'categories' => ['View Categories', 'Create Categories', 'Edit Categories', 'Delete Categories'],
            'customers' => ['View Customers', 'Create Customers', 'Edit Customers', 'Delete Customers'],
            'inventory' => ['View Inventory', 'Manage Stock', 'Transfer Stock', 'View Reports'],
            'marketing' => ['View Marketing', 'Create Campaigns', 'Manage Coupons', 'View Analytics'],
            'reports' => ['View Reports', 'Export Reports', 'Sales Reports', 'Revenue Reports'],
            'settings' => ['View Settings', 'Edit Settings', 'Manage Users', 'Manage Roles'],
        ];

        // Create permissions
        $permissions = [];
        foreach ($modules as $module => $perms) {
            foreach ($perms as $perm) {
                $permName = strtolower(str_replace(' ', '.', $module . '.' . $perm));
                $permissions[$permName] = Permission::create([
                    'name' => $permName,
                    'display_name' => $perm,
                    'module' => ucfirst($module),
                ]);
            }
        }

        // Create roles
        $superAdmin = Role::create([
            'name' => 'super_admin',
            'display_name' => 'Super Admin',
            'description' => 'Full access to all features',
        ]);

        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Access to most features',
        ]);

        $manager = Role::create([
            'name' => 'manager',
            'display_name' => 'Manager',
            'description' => 'Limited management access',
        ]);

        $staff = Role::create([
            'name' => 'staff',
            'display_name' => 'Staff',
            'description' => 'Basic access',
        ]);

        // Assign all permissions to Super Admin
        $superAdmin->permissions()->attach(Permission::all());

        // Assign specific permissions to Admin
        $adminPermissions = Permission::whereIn('module', ['Orders', 'Products', 'Categories', 'Customers', 'Inventory', 'Marketing'])->get();
        $admin->permissions()->attach($adminPermissions);

        // Assign specific permissions to Manager
        $managerPermissions = Permission::whereIn('module', ['Orders', 'Products', 'Customers'])->get();
        $manager->permissions()->attach($managerPermissions);

        // Assign basic permissions to Staff
        $staffPermissions = Permission::where('display_name', 'LIKE', 'View%')->get();
        $staff->permissions()->attach($staffPermissions);
    }
}
