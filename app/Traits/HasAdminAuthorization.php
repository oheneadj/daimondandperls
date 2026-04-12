<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasAdminAuthorization
{
    protected function authorizePermission(string $permission): void
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return;
        }

        if (! $user->hasPermission($permission)) {
            abort(403, 'You do not have permission to access this page.');
        }
    }
}
