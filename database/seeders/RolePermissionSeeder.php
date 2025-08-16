<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions based on your business logic
        $permissions = [
            // User Management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',

            // Store Management
            'view-stores',
            'create-stores',
            'edit-stores',
            'delete-stores',

            // Salper Management
            'view-salpers',
            'create-salpers',
            'edit-salpers',
            'delete-salpers',

            // Deal Management
            'view-deals',
            'create-deals',
            'edit-deals',
            'delete-deals',
            'approve-deals',

            // Customer Management
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',

            // Item Management
            'view-items',
            'create-items',
            'edit-items',
            'delete-items',

            // Point Management
            'view-points',
            'create-points',
            'edit-points',
            'delete-points',

            // Bobot Management
            'view-bobots',
            'create-bobots',
            'edit-bobots',
            'delete-bobots',

            // Reports
            'view-reports',
            'view-regional-reports',
            'view-national-reports',
            'export-reports',

            // System Settings
            'manage-settings',
            'manage-permissions',
            'view-audit-logs',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 1. STAFF - Basic operations in their store
        $staffRole = Role::create(['name' => 'staff']);
        $staffRole->givePermissionTo([
            'view-deals',
            'create-deals',
            'edit-deals',
            'view-customers',
            'create-customers',
            'edit-customers',
            'view-items',
            'view-points',
        ]);

        // 2. LEADERS - Staff permissions + manage staff in their store
        $leadersRole = Role::create(['name' => 'leaders']);
        $leadersRole->givePermissionTo([
            'view-deals',
            'create-deals',
            'edit-deals',
            'delete-deals',
            'approve-deals',
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',
            'view-items',
            'create-items',
            'edit-items',
            'view-points',
            'create-points',
            'edit-points',
            'view-salpers',
            'create-salpers',
            'edit-salpers',
            'view-reports',
        ]);

        // 3. MANAGER - Leaders permissions + manage store operations
        $managerRole = Role::create(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'view-deals',
            'create-deals',
            'edit-deals',
            'delete-deals',
            'approve-deals',
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',
            'view-items',
            'create-items',
            'edit-items',
            'delete-items',
            'view-points',
            'create-points',
            'edit-points',
            'delete-points',
            'view-bobots',
            'create-bobots',
            'edit-bobots',
            'view-salpers',
            'create-salpers',
            'edit-salpers',
            'delete-salpers',
            'view-users',
            'create-users',
            'edit-users',
            'view-stores',
            'edit-stores',
            'view-reports',
            'export-reports',
        ]);

        // 4. REGIONAL MANAGER - Manager permissions + regional oversight
        $regionalManagerRole = Role::create(['name' => 'regional manager']);
        $regionalManagerRole->givePermissionTo([
            'view-deals',
            'create-deals',
            'edit-deals',
            'delete-deals',
            'approve-deals',
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',
            'view-items',
            'create-items',
            'edit-items',
            'delete-items',
            'view-points',
            'create-points',
            'edit-points',
            'delete-points',
            'view-bobots',
            'create-bobots',
            'edit-bobots',
            'delete-bobots',
            'view-salpers',
            'create-salpers',
            'edit-salpers',
            'delete-salpers',
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'view-stores',
            'create-stores',
            'edit-stores',
            'delete-stores',
            'view-reports',
            'view-regional-reports',
            'export-reports',
        ]);

        // 5. SUPER ADMIN - Full system access
        $superAdminRole = Role::create(['name' => 'super admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Migrate existing users to Spatie roles based on their current role field
        $this->migrateExistingUsers();
    }

    private function migrateExistingUsers()
    {
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role) {
                // Clean up role name and assign
                $roleName = strtolower(trim($user->role));

                // Handle possible variations in naming
                switch ($roleName) {
                    case 'staff':
                    case 'employee':
                        $user->assignRole('staff');
                        break;
                    case 'leader':
                    case 'leaders':
                    case 'team leader':
                        $user->assignRole('leaders');
                        break;
                    case 'manager':
                    case 'store manager':
                        $user->assignRole('manager');
                        break;
                    case 'regional manager':
                    case 'regional':
                    case 'rm':
                        $user->assignRole('regional manager');
                        break;
                    case 'super admin':
                    case 'superadmin':
                    case 'admin':
                    case 'administrator':
                        $user->assignRole('super admin');
                        break;
                    default:
                        // Default to staff if role doesn't match
                        $user->assignRole('staff');
                        break;
                }
            }
        }
    }
}