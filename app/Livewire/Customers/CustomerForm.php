<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Traits\HasAdminAuthorization;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Customer Form')]
#[Layout('layouts.admin')]
class CustomerForm extends Component
{
    use HasAdminAuthorization;

    public ?Customer $customer = null;

    public ?string $name = '';

    public ?string $email = '';

    public ?string $phone = '';

    public bool $isEditing = false;

    public bool $is_active = true;

    public bool $hasUser = false;

    public string $loading = '';

    public function mount(?Customer $customer = null): void
    {
        $this->authorizePermission('manage_customers');
        if ($customer && $customer->exists) {
            $this->customer = $customer;
            $this->name = $customer->name;
            $this->email = $customer->email;
            $this->phone = $customer->phone;
            $this->isEditing = true;

            if ($customer->user) {
                $this->hasUser = true;
                $this->is_active = $customer->user->is_active;
            }
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:customers,email,'.($this->customer->id ?? 'NULL')],
            'phone' => ['required', 'string', 'max:20', 'unique:customers,phone,'.($this->customer->id ?? 'NULL')],
            'is_active' => ['boolean'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            $this->customer->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ]);

            if ($this->customer->user) {
                $this->customer->user->update(['is_active' => $this->is_active]);
            }

            $this->dispatch('toast', ['type' => 'success', 'message' => __('Customer updated successfully.')]);
        } else {
            Customer::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ]);

            $this->dispatch('toast', ['type' => 'success', 'message' => __('Customer created successfully.')]);
        }

        $this->redirect(route('admin.customers.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.customers.form');
    }
}
