<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function assignRole(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', '=', $role)->firstOrFail();
        }

        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            return $this->roles->contains('slug', $roles);
        }

        return (bool) $this->roles->pluck('slug')->intersect($roles)->count();
    }

    public function hasPermission(string $permission): bool
    {
        if (! $this->relationLoaded('roles')) {
            $this->load('roles.permissions');
        }

        foreach ($this->roles as $role) {
            if (! $role->relationLoaded('permissions')) {
                $role->load('permissions');
            }

            if ($role->permissions->contains('slug', $permission)) {
                return true;
            }
        }

        return false;
    }
}
