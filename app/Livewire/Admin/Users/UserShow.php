<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Traits\HasAdminAuthorization;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('User Profile - DPC')]
class UserShow extends Component
{
    use HasAdminAuthorization;

    public User $user;

    public function mount(User $user): void
    {
        $this->authorizePermission('manage_users');
        $this->user = $user;
    }

    public function render(): View
    {
        $this->user->loadMissing(['roles']);

        return view('livewire.admin.users.user-show');
    }
}
