<?php

namespace App\Livewire\Customers;

use App\Enums\UserRole;
use App\Models\Customer;
use App\Traits\HasAdminAuthorization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Customers')]
#[Layout('layouts.admin')]
class CustomerIndex extends Component
{
    use HasAdminAuthorization;
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $role = 'all'; // all, registered, guest

    #[Url(history: true)]
    public string $status = 'all'; // all, active, inactive

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    // Edit Modal Properties
    public bool $showEditModal = false;

    public ?Customer $selectedCustomer = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $loading = '';

    // Security Confirmation Properties
    public bool $showConfirmationModal = false;

    public string $sensitiveAction = ''; // 'delete' or 'toggleStatus'

    public string $confirmationPassword = '';

    public ?Customer $actionTarget = null;

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

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRole(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function delete(Customer $customer): void
    {
        // This is handled by executeSensitiveAction
    }

    public function startAction(Customer $customer, string $action): void
    {
        if (Auth::user()->role !== UserRole::SuperAdmin) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => __('Only Super Admins can perform this action.'),
            ]);

            return;
        }

        $this->actionTarget = $customer;
        $this->sensitiveAction = $action;
        $this->confirmationPassword = '';
        $this->showConfirmationModal = true;
    }

    public function executeSensitiveAction(): void
    {
        if (Auth::user()->role !== UserRole::SuperAdmin) {
            abort(403);
        }

        if (! Hash::check($this->confirmationPassword, Auth::user()->password)) {
            $this->dispatch('toast', ['type' => 'error', 'message' => __('The password you entered is incorrect.')]);

            return;
        }

        if ($this->sensitiveAction === 'delete') {
            $this->actionTarget->delete();
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => __('Customer removed successfully.'),
            ]);
        } elseif ($this->sensitiveAction === 'toggleStatus') {
            $user = $this->actionTarget->user;
            if ($user) {
                $user->update(['is_active' => ! $user->is_active]);
                $this->dispatch('toast', [
                    'type' => 'success',
                    'message' => $user->is_active ? __('Account enabled successfully.') : __('Account disabled successfully.'),
                ]);
            }
        }

        $this->showConfirmationModal = false;
        $this->reset(['actionTarget', 'sensitiveAction', 'confirmationPassword']);
    }

    public function edit(Customer $customer): void
    {
        $this->selectedCustomer = $customer;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->showEditModal = true;
    }

    public function update(): void
    {
        $this->loading = 'update';

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers,email,'.$this->selectedCustomer->id],
            'phone' => ['required', 'string', 'max:20', 'unique:customers,phone,'.$this->selectedCustomer->id],
        ]);

        $this->selectedCustomer->update($validated);

        $this->showEditModal = false;
        $this->reset(['name', 'email', 'phone', 'selectedCustomer', 'loading']);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => __('Customer updated successfully.'),
        ]);
    }

    public function closeModal(): void
    {
        $this->showEditModal = false;
        $this->reset(['name', 'email', 'phone', 'selectedCustomer']);
        $this->resetErrorBag();
    }

    public function render()
    {
        $stats = [
            'total' => Customer::count('*'),
            'new_this_month' => Customer::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count(),
            'most_active' => Customer::withCount('bookings')->orderByDesc('bookings_count')->first()?->name ?? 'N/A',
        ];

        $query = Customer::query()
            ->withCount('bookings');

        if (filled($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('phone', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->role !== 'all') {
            if ($this->role === 'registered') {
                $query->whereNotNull('user_id');
            } elseif ($this->role === 'guest') {
                $query->whereNull('user_id');
            }
        }

        if ($this->status !== 'all') {
            $query->whereHas('user', function ($q) {
                $q->where('is_active', $this->status === 'active');
            });
            // If they filter by active/inactive, they implicitly mean registered users
            if ($this->role === 'all') {
                $query->whereNotNull('user_id');
            }
        }

        $customers = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.customers.index', [
            'customers' => $customers,
            'stats' => $stats,
        ]);
    }
}
