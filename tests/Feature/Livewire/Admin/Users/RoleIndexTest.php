<?php

use App\Livewire\Admin\Users\RoleIndex;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    // Ensure permissions exist
    Permission::updateOrCreate(['slug' => 'manage_bookings'], ['name' => 'Manage Bookings', 'description' => 'Test']);
    Permission::updateOrCreate(['slug' => 'manage_users'], ['name' => 'Manage Users', 'description' => 'Test']);
    
    $this->role = Role::create([
        'name' => 'Test Role',
        'slug' => 'test-role',
        'description' => 'Test Description'
    ]);
});

test('it can render the role index page', function () {
    Livewire::test(RoleIndex::class)
        ->assertStatus(200)
        ->assertSee('Test Role');
});

test('it can select a role and see permissions', function () {
    Livewire::test(RoleIndex::class)
        ->call('selectRole', $this->role->id)
        ->assertSet('selectedRole.id', $this->role->id)
        ->assertSee('Test Role');
});

test('it can save permissions for a role', function () {
    $permission = Permission::where('slug', 'manage_bookings')->first();
    
    Livewire::test(RoleIndex::class)
        ->call('selectRole', $this->role->id)
        ->set('rolePermissions', [$permission->id])
        ->call('savePermissions')
        ->assertHasNoErrors();
        
    expect($this->role->fresh()->permissions->contains($permission->id))->toBeTrue();
});

test('it can create a new role', function () {
    Livewire::test(RoleIndex::class)
        ->set('name', 'New Manager')
        ->set('description', 'Manages things')
        ->call('createRole')
        ->assertSet('showCreateModal', false);
        
    expect(Role::where('name', 'New Manager')->exists())->toBeTrue();
});
