<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define Permissions
        $permissions = [
            // Operations
            'manage_bookings' => ['name' => 'Manage Bookings',   'description' => 'View, confirm, update, and cancel meal and event bookings'],
            'manage_events' => ['name' => 'Manage Events',     'description' => 'View and manage event inquiry bookings'],
            'manage_packages' => ['name' => 'Manage Packages',   'description' => 'Create, edit, and delete catering packages'],
            'manage_categories' => ['name' => 'Manage Categories', 'description' => 'Create and manage package categories and delivery windows'],
            'manage_customers' => ['name' => 'Manage Customers',  'description' => 'View and edit customer profiles and history'],
            // Finance
            'manage_payments' => ['name' => 'Manage Payments',   'description' => 'View and process payments, verify transactions'],
            'manage_reports' => ['name' => 'Manage Reports',    'description' => 'Access financial reports, revenue analytics, and exports'],
            // Administration
            'manage_users' => ['name' => 'Manage Users',      'description' => 'Invite, edit, and deactivate administrative users'],
            'manage_roles' => ['name' => 'Manage Roles',      'description' => 'Create roles and assign permissions (Super Admin only)'],
            'manage_settings' => ['name' => 'Manage Settings',   'description' => 'Update business info, API keys, and application settings'],
        ];

        foreach ($permissions as $slug => $data) {
            \App\Models\Permission::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                ]
            );
        }

        // Define Roles
        $roles = [
            'super_admin' => [
                'name' => 'Super Admin',
                'description' => 'Unrestricted access to all system features and configuration',
                'permissions' => array_keys($permissions),
            ],
            'admin' => [
                'name' => 'Admin',
                'description' => 'Full operational access excluding system-level settings',
                'permissions' => [
                    'manage_bookings', 'manage_events', 'manage_packages',
                    'manage_categories', 'manage_customers', 'manage_payments', 'manage_reports',
                ],
            ],
            'staff' => [
                'name' => 'Staff',
                'description' => 'Day-to-day operational access for bookings and customers',
                'permissions' => ['manage_bookings', 'manage_events', 'manage_customers'],
            ],
        ];

        foreach ($roles as $slug => $data) {
            $role = \App\Models\Role::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                ]
            );

            $permissionIds = \App\Models\Permission::whereIn('slug', $data['permissions'])->pluck('id');
            $role->permissions()->sync($permissionIds);
        }

        // Assign Super Admin role to the seeded super_admin user
        $superAdminUser = \App\Models\User::where('role', \App\Enums\UserRole::SuperAdmin)->first();
        if ($superAdminUser) {
            $superAdminRole = \App\Models\Role::where('slug', 'super_admin')->first();
            if ($superAdminRole) {
                $superAdminUser->roles()->syncWithoutDetaching([$superAdminRole->id]);
            }
        }
    }
}
