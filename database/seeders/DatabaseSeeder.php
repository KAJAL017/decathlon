<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Roles and Permissions first
        $this->call(RolePermissionSeeder::class);

        // Get Super Admin role
        $superAdminRole = \App\Models\Role::where('name', 'super_admin')->first();

        // Create Admin User with Super Admin role
        User::create([
            'name' => 'Super Admin',
            'email' => 'super@gmail.com',
            'password' => bcrypt('2580'),
            'email_verified_at' => now(),
            'role_id' => $superAdminRole->id,
            'is_active' => true,
        ]);

        // Create Test Users with random roles
        $roles = \App\Models\Role::all();
        User::factory(10)->create()->each(function ($user) use ($roles) {
            $user->role_id = $roles->random()->id;
            $user->is_active = true;
            $user->save();
        });
    }
}
