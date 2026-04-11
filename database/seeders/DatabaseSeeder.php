<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'role' => \App\Enums\UserRole::SuperAdmin,
        ]);

        $this->call([
            RolesAndPermissionsSeeder::class,
            SettingsSeeder::class,
            CateringPackageSeeder::class,
        ]);

        // \App\Models\Package::factory(5)->create();
        \App\Models\Customer::factory(10)->create();
        \App\Models\Booking::factory(20)->create();
    }
}
