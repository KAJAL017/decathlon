<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class PermissionUpdateSeeder extends Seeder
{
    public function run(): void
    {
        // New modules added after initial setup
        $newModules = [
            'collections'     => ['View Collections', 'Create Collections', 'Edit Collections', 'Delete Collections'],
            'reviews'         => ['View Reviews', 'Create Reviews', 'Edit Reviews', 'Delete Reviews', 'Approve Reviews'],
            'promotions'      => ['View Promotions', 'Create Promotions', 'Edit Promotions', 'Delete Promotions'],
            'coupons'         => ['View Coupons', 'Create Coupons', 'Edit Coupons', 'Delete Coupons'],
            'email_campaigns' => ['View Campaigns', 'Create Campaigns', 'Edit Campaigns', 'Delete Campaigns', 'Send Campaigns'],
            'stock'           => ['View Stock', 'Manage Stock', 'Adjust Stock', 'View Stock History'],
            'warehouses'      => ['View Warehouses', 'Create Warehouses', 'Edit Warehouses', 'Delete Warehouses'],
            'customers_mgmt'  => ['View Customers', 'Edit Customers', 'Delete Customers', 'Export Customers'],
            'reports'         => ['View Reports', 'Export Reports', 'View Analytics'],
            'settings'        => ['View Settings', 'Edit Settings', 'Manage Integrations'],
            'ai_tools'        => ['Use AI Tools', 'View AI Usage'],
            'webhooks'        => ['View Webhooks', 'Create Webhooks', 'Edit Webhooks', 'Delete Webhooks', 'Test Webhooks'],
            'system'          => ['View System Tools', 'Clear Cache', 'View Logs', 'Clear Logs'],
            'brands'          => ['View Brands', 'Create Brands', 'Edit Brands', 'Delete Brands'],
            'tags'            => ['View Tags', 'Create Tags', 'Edit Tags', 'Delete Tags'],
            'attributes'      => ['View Attributes', 'Create Attributes', 'Edit Attributes', 'Delete Attributes'],
        ];

        $newPermissions = [];
        foreach ($newModules as $module => $perms) {
            foreach ($perms as $perm) {
                $permName = strtolower(str_replace([' ', '/'], ['.', '_'], $module . '.' . $perm));

                // Skip if already exists
                if (Permission::where('name', $permName)->exists()) continue;

                $newPermissions[] = Permission::create([
                    'name'         => $permName,
                    'display_name' => $perm,
                    'module'       => ucfirst(str_replace('_', ' ', $module)),
                ]);
            }
        }

        $this->command->info('Created ' . count($newPermissions) . ' new permissions');

        // Give Super Admin all new permissions
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $allPerms = Permission::all();
            $superAdmin->permissions()->sync($allPerms);
            $this->command->info('Super Admin synced with all ' . $allPerms->count() . ' permissions');
        }

        // Give Admin the new module permissions (except system/ai/webhooks)
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $adminModules = ['Collections', 'Reviews', 'Promotions', 'Coupons', 'Email Campaigns',
                             'Stock', 'Warehouses', 'Customers Mgmt', 'Reports', 'Brands', 'Tags', 'Attributes'];
            $adminPerms = Permission::whereIn('module', $adminModules)->get();
            $admin->permissions()->syncWithoutDetaching($adminPerms);
            $this->command->info('Admin updated with ' . $adminPerms->count() . ' permissions');
        }

        // Give Manager limited new permissions
        $manager = Role::where('name', 'manager')->first();
        if ($manager) {
            $managerPerms = Permission::where('display_name', 'LIKE', 'View%')
                ->whereIn('module', ['Collections', 'Reviews', 'Stock', 'Reports', 'Brands'])
                ->get();
            $manager->permissions()->syncWithoutDetaching($managerPerms);
        }

        $this->command->info('Total permissions now: ' . Permission::count());
    }
}
