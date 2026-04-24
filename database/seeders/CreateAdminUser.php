<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class CreateAdminUser extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super_admin')->first();

        // Create Super Admin if not exists
        if (!User::where('email', 'super@gmail.com')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'super@gmail.com',
                'password' => bcrypt('2580'),
                'email_verified_at' => now(),
                'role_id' => $superAdminRole->id,
                'is_active' => true,
            ]);
            echo "Super Admin created successfully!\n";
        }

        // Create some test users
        $roles = Role::all();
        
        $testUsers = [
            ['name' => 'John Admin', 'email' => 'admin@gmail.com', 'role' => 'admin'],
            ['name' => 'Jane Manager', 'email' => 'manager@gmail.com', 'role' => 'manager'],
            ['name' => 'Bob Staff', 'email' => 'staff@gmail.com', 'role' => 'staff'],
        ];

        foreach ($testUsers as $userData) {
            if (!User::where('email', $userData['email'])->exists()) {
                $role = Role::where('name', $userData['role'])->first();
                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => bcrypt('2580'),
                    'email_verified_at' => now(),
                    'role_id' => $role->id,
                    'is_active' => true,
                ]);
                echo "{$userData['name']} created successfully!\n";
            }
        }
    }
}
