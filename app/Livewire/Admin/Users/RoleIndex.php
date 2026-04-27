<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Users;

use App\Enums\UserRole;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\HasAdminAuthorization;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Roles & Permissions')]
class RoleIndex extends Component
{
    use HasAdminAuthorization;

    public $roles;

    public $permissions;

    public ?Role $selectedRole = null;

    public array $rolePermissions = [];

    // Create modal
    public bool $showCreateModal = false;

    public string $name = '';

    public string $description = '';

    // Edit modal
    public bool $showEditModal = false;

    public string $editName = '';

    public string $editDescription = '';

    public function mount(): void
    {
        $this->authorizePermission('manage_roles');
        $this->loadData();

        if ($this->roles->count() > 0) {
            $this->selectRole($this->roles->first()->id);
        }
    }

    public function loadData(): void
    {
        $this->roles = Role::with('permissions')->get();
        $this->permissions = Permission::all();
    }

    public function selectRole(int $roleId): void
    {
        $this->selectedRole = Role::with('permissions')->find($roleId);
        $this->rolePermissions = $this->selectedRole ? $this->selectedRole->permissions->pluck('id')->toArray() : [];
    }

    public function isSuperAdmin(): bool
    {
        return Auth::user()?->role === UserRole::SuperAdmin;
    }

    public function createRole(): void
    {
        abort_unless($this->isSuperAdmin(), 403);

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

        $this->dispatch('toast', type: 'success', message: "Role \"{$role->name}\" created successfully.");
    }

    public function openEditModal(): void
    {
        abort_unless($this->isSuperAdmin(), 403);

        if (! $this->selectedRole || $this->selectedRole->slug === 'super_admin') {
            return;
        }

        $this->editName = $this->selectedRole->name;
        $this->editDescription = $this->selectedRole->description;
        $this->showEditModal = true;
    }

    public function updateRole(): void
    {
        abort_unless($this->isSuperAdmin(), 403);

        if (! $this->selectedRole || $this->selectedRole->slug === 'super_admin') {
            return;
        }

        $this->validate([
            'editName' => 'required|string|max:255|unique:roles,name,'.$this->selectedRole->id,
            'editDescription' => 'required|string|max:255',
        ]);

        $this->selectedRole->update([
            'name' => $this->editName,
            'description' => $this->editDescription,
        ]);

        $this->loadData();
        $this->selectRole($this->selectedRole->id);
        $this->reset(['editName', 'editDescription', 'showEditModal']);

        $this->dispatch('toast', type: 'success', message: 'Role updated successfully.');
    }

    public function deleteRole(Role $role): void
    {
        abort_unless($this->isSuperAdmin(), 403);

        if ($role->slug === 'super_admin') {
            $this->dispatch('toast', type: 'error', message: 'The Super Admin role cannot be deleted.');

            return;
        }

        if ($role->users()->exists()) {
            $this->dispatch('toast', type: 'error', message: 'Cannot delete a role that is assigned to users.');

            return;
        }

        $roleName = $role->name;
        $role->delete();
        $this->loadData();

        if ($this->roles->count() > 0) {
            $this->selectRole($this->roles->first()->id);
        } else {
            $this->selectedRole = null;
            $this->rolePermissions = [];
        }

        $this->dispatch('toast', type: 'success', message: "Role \"{$roleName}\" deleted.");
    }

    public function savePermissions(): void
    {
        abort_unless($this->isSuperAdmin(), 403);

        if (! $this->selectedRole || $this->selectedRole->slug === 'super_admin') {
            return;
        }

        $this->selectedRole->permissions()->sync($this->rolePermissions);
        $this->selectRole($this->selectedRole->id);

        $this->dispatch('toast', type: 'success', message: "Permissions updated for \"{$this->selectedRole->name}\".");
    }

    public function render(): View
    {
        $permissionsGrouped = [
            'Operations' => $this->permissions->whereIn('slug', [
                'manage_bookings', 'manage_events', 'manage_packages', 'manage_categories', 'manage_customers',
            ]),
            'Finance' => $this->permissions->whereIn('slug', [
                'manage_payments', 'manage_reports',
            ]),
            'Administration' => $this->permissions->whereIn('slug', [
                'manage_users', 'manage_roles', 'manage_settings', 'view_error_logs',
            ]),
            'Contact' => $this->permissions->whereIn('slug', [
                'manage_contact_messages', 'receive_contact_notifications',
            ]),
        ];

        return view('livewire.admin.users.role-index', [
            'permissionsGrouped' => $permissionsGrouped,
        ]);
    }
}
