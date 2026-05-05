<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    /**
     * Seeds only what is required for the live production environment:
     * - Super admin user (idempotent — skipped if already exists)
     * - Roles & permissions
     * - Application settings
     * - Real catering packages & categories
     */
    public function run(): void
    {
        // Super admin — only create if no super_admin exists yet
        if (! User::where('role', UserRole::SuperAdmin)->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@diamondsandpearlsgh.com',
                'password' => Hash::make(env('ADMIN_PASSWORD', '@password@')),
                'role' => UserRole::SuperAdmin,
                'email_verified_at' => now(),
            ]);
        }

        $this->call([
            RolesAndPermissionsSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
