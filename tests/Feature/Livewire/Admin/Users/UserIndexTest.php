<?php

use App\Livewire\Admin\Users\UserIndex;
use App\Models\User;
use App\Models\Role;
use Livewire\Livewire;
use Illuminate\Support\Str;

beforeEach(function () {
    // Create an admin user
    $this->user = User::factory()->create([
        'type' => \App\Enums\UserType::Admin,
        'is_active' => true,
    ]);
    
    $this->actingAs($this->user);
    
    // Ensure roles exist
    Role::updateOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin', 'description' => 'Test']);
    Role::updateOrCreate(['slug' => 'manager'], ['name' => 'Manager', 'description' => 'Test']);
});

test('it can render the user index page with dropdowns', function () {
    Livewire::test(UserIndex::class)
        ->assertStatus(200)
        ->assertSee('User Management')
        ->assertSee($this->user->name)
        ->assertSee('View Profile')
        ->assertSee('Edit User');
});

test('it can search for users by name', function () {
    $otherUser = User::factory()->create([
        'name' => 'Specific Search Name',
        'email' => 'search@example.com'
    ]);
    
    Livewire::test(UserIndex::class)
        ->set('search', 'Specific Search Name')
        ->assertSee('Specific Search Name')
        ->assertDontSee($this->user->name);
});

test('it can filter users by role', function () {
    $role = Role::where('slug', 'manager')->first();
    $managerUser = User::factory()->create(['name' => 'Manager User']);
    $managerUser->roles()->attach($role);
    
    Livewire::test(UserIndex::class)
        ->set('role', 'manager')
        ->assertSee('Manager User')
        ->assertDontSee($this->user->name);
});
