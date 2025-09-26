<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles (unique by name + guard_name)
        $roles = [
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'staff', 'guard_name' => 'web'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name'], 'guard_name' => $role['guard_name']]
            );
        }

        // Create super admin if not exists
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@outlook.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('admin'),
            ]
        );

        // Assign role if not already assigned
        if (! $superAdmin->hasRole('admin')) {
            $superAdmin->assignRole('admin');
        }
    }
}
