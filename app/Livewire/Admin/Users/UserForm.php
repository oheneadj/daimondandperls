<?php

namespace App\Livewire\Admin\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Manage User')]
class UserForm extends Component
{
    public ?User $user = null;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public ?int $selectedRole = null;
    public bool $is_active = true;

    public function mount(?User $user = null): void
    {
        if ($user && $user->exists) {
            $this->user = $user;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->selectedRole = $user->roles->first()?->id;
            $this->is_active = $user->is_active;
        }
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($this->user?->id),
            ],
            'phone' => [
                'required', 'string', 'max:20',
                Rule::unique('users', 'phone')->ignore($this->user?->id),
            ],
            'selectedRole' => 'required|integer|exists:roles,id',
            'is_active' => 'boolean',
        ]);

        if ($this->user) {
            $this->user->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'is_active' => $this->is_active,
            ]);
            $user = $this->user;
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                'is_active' => $this->is_active,
            ]);
        }

        $user->roles()->sync([$this->selectedRole]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $this->user ? 'User updated successfully.' : 'User invited successfully.',
        ]);

        $this->redirectRoute('admin.users.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.users.user-form', [
            'roles' => Role::all(),
        ]);
    }
}
