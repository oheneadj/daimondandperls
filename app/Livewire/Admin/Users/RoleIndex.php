<?php

namespace App\Livewire\Admin\Users;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Roles & Permissions')]
class RoleIndex extends Component
{
    public $roles;

    public $permissions;

    public ?Role $selectedRole = null;

    public array $rolePermissions = [];

    // Create Role properties
    public bool $showCreateModal = false;

    public string $name = '';

    public string $description = '';

    public function mount(): void
    {
        $this->loadData();

        if ($this->roles->count() > 0) {
            $this->selectRole($this->roles->first()->id);
        }
    }

    public function loadData(): void
    {
        $this->roles = Role::all();
        $this->permissions = Permission::all();
    }

    public function selectRole(int $roleId): void
    {
        $this->selectedRole = Role::with('permissions')->find($roleId);
        $this->rolePermissions = $this->selectedRole ? $this->selectedRole->permissions->pluck('id')->toArray() : [];
    }

    public function createRole(): void
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'required|string|max:255',
        ]);

        $role = Role::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
        ]);

        $this->loadData();
        $this->selectRole($role->id);
        $this->reset(['name', 'description', 'showCreateModal']);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Role created successfully.',
        ]);
    }

    public function deleteRole(Role $role): void
    {
        if ($role->slug === 'super_admin') {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'The Super Admin role cannot be deleted.',
            ]);

            return;
        }

        if ($role->users()->exists()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Cannot delete a role that is assigned to users.',
            ]);

            return;
        }

        $role->delete();
        $this->loadData();

        if ($this->roles->count() > 0) {
            $this->selectRole($this->roles->first()->id);
        } else {
            $this->selectedRole = null;
            $this->rolePermissions = [];
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Role deleted successfully.',
        ]);
    }

    public function savePermissions(): void
    {
        if (! $this->selectedRole) {
            return;
        }

        $this->selectedRole->permissions()->sync($this->rolePermissions);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Permissions updated for '.$this->selectedRole->name,
        ]);
    }

    public function render(): View
    {
        $permissionsGrouped = [
            'Operations' => $this->permissions->whereIn('slug', ['manage_bookings', 'manage_packages', 'manage_customers']),
            'Analytics' => $this->permissions->whereIn('slug', ['manage_reports']),
            'Administration' => $this->permissions->whereIn('slug', ['manage_users', 'manage_settings']),
        ];

        return view('livewire.admin.users.role-index', [
            'permissionsGrouped' => $permissionsGrouped,
        ]);
    }
}
