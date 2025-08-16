<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Define roles
        $roles = [
            'staff',
            'leaders',
            'manager',
            'regional manager',
            'super admin',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Seed example users for each role
        $users = [
            [
                'username' => 'staff1',
                'email' => 'staff@example.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ],
            [
                'username' => 'leader1',
                'email' => 'leader@example.com',
                'password' => Hash::make('password'),
                'role' => 'leaders',
            ],
            [
                'username' => 'manager1',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ],
            [
                'username' => 'regional1',
                'email' => 'regional@example.com',
                'password' => Hash::make('password'),
                'role' => 'regional manager',
            ],
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'super admin',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'username' => $data['username'],
                    'password' => $data['password'],
                ]
            );
            $user->assignRole($data['role']);
        }
    }
}
