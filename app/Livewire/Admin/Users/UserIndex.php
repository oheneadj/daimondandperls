<?php

namespace App\Livewire\Admin\Users;

use App\Enums\UserType;
use App\Models\Role;
use App\Models\User;
use App\Notifications\AdminInvitationNotification;
use App\Traits\HasAdminAuthorization;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('User Management')]
class UserIndex extends Component
{
    use HasAdminAuthorization;
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $role = '';

    #[Url(history: true)]
    public string $status = '';

    #[Url(history: true)]
    public string $sortField = 'created_at';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    public bool $showConfirmationModal = false;

    public string $sensitiveAction = ''; // 'delete' or 'toggleStatus'

    public string $confirmationPassword = '';

    public ?User $actionTarget = null;

    public bool $showResendModal = false;

    public ?User $resendTarget = null;

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRole(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function startAction(User $user, string $action): void
    {
        if ($user->id === auth()->id() && $action === 'delete') {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'You cannot delete yourself.',
            ]);

            return;
        }

        if ($user->hasRole('super_admin') && ! auth()->user()->hasRole('super_admin')) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Only super admins can modify other super admins.',
            ]);

            return;
        }

        $this->actionTarget = $user;
        $this->sensitiveAction = $action;
        $this->confirmationPassword = '';
        $this->showConfirmationModal = true;
    }

    public function executeSensitiveAction(): void
    {
        if (! Hash::check($this->confirmationPassword, Auth::user()->password)) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => __('The password you entered is incorrect.'),
            ]);

            return;
        }

        if ($this->sensitiveAction === 'delete') {
            $this->actionTarget->delete();
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'User deleted successfully.',
            ]);
        } elseif ($this->sensitiveAction === 'toggleStatus') {
            $this->actionTarget->update(['is_active' => ! $this->actionTarget->is_active]);
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => $this->actionTarget->is_active ? 'User enabled successfully.' : 'User disabled successfully.',
            ]);
        }

        $this->showConfirmationModal = false;
        $this->reset(['actionTarget', 'sensitiveAction', 'confirmationPassword']);
    }

    public function confirmResendInvite(User $user): void
    {
        $this->resendTarget = $user;
        $this->showResendModal = true;
    }

    public function resendInvite(): void
    {
        if (! $this->resendTarget) {
            return;
        }

        $temporaryPassword = Str::password(16);
        $token = Str::random(64);

        $this->resendTarget->update([
            'password' => $temporaryPassword,
            'must_change_password' => true,
            'invitation_token' => $token,
            'invitation_sent_at' => now(),
            'invitation_accepted_at' => null,
        ]);

        $this->resendTarget->notify(new AdminInvitationNotification(
            $temporaryPassword,
            route('invitation.accept', $token)
        ));

        $this->showResendModal = false;
        $this->reset(['resendTarget']);

        $this->dispatch('toast', type: 'success', message: 'Invitation resent successfully.');
    }

    public function mount(): void
    {
        $this->authorizePermission('manage_users');
    }

    public function render(): View
    {
        $users = User::query()
            ->where('type', UserType::Admin)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->role, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('slug', $this->role);
                });
            })
            ->when($this->status !== '', function ($query) {
                $query->where('is_active', (bool) $this->status);
            })
            ->with('roles')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $roles = Role::all();

        return view('livewire.admin.users.user-index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}
