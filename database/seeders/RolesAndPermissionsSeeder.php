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
            'manage_bookings' => 'Can view, confirm, and manage bookings',
            'manage_packages' => 'Can create, edit, and delete packages',
            'manage_customers' => 'Can manage customer profiles',
            'manage_reports' => 'Can view financial and booking reports',
            'manage_users' => 'Can manage administrative users and roles',
            'manage_settings' => 'Can update general application settings',
        ];

        foreach ($permissions as $slug => $description) {
            \App\Models\Permission::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => str($slug)->replace('_', ' ')->title()->value(),
                    'description' => $description,
                ]
            );
        }

        // Define Roles
        $roles = [
            'super_admin' => [
                'name' => 'Super Admin',
                'description' => 'Full access to all system features',
                'permissions' => array_keys($permissions),
            ],
            'admin' => [
                'name' => 'Admin',
                'description' => 'Can manage daily operations',
                'permissions' => ['manage_bookings', 'manage_packages', 'manage_customers', 'manage_reports'],
            ],
            'staff' => [
                'name' => 'Staff',
                'description' => 'Limited operational access',
                'permissions' => ['manage_bookings', 'manage_customers'],
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

        // Assign Super Admin role to existing super_admin user if exists
        $superAdminUser = \App\Models\User::where('email', 'admin@dpc.com')->first();
        if ($superAdminUser) {
            $superAdminRole = \App\Models\Role::where('slug', 'super_admin')->first();
            $superAdminUser->roles()->sync([$superAdminRole->id]);
        }
    }
}
