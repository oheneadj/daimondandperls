<?php

declare(strict_types=1);

namespace App\Livewire\Customer;

use App\Enums\NotificationPreference;
use App\Traits\ResolvesCustomer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.customer')]
#[Title('Profile Settings')]
class Profile extends Component
{
    use ResolvesCustomer;

    public string $name = '';

    public ?string $email = '';

    public string $phone = '';

    public string $notificationPreference = 'email';

    public function mount(): void
    {
        $user = Auth::user();
        $customer = $this->resolveCustomer();

        $this->name = $customer?->name ?? $user->name ?? '';
        $this->email = $customer?->email ?? $user->email ?? '';
        $this->phone = $customer?->phone ?? $user->phone ?? '';
        $this->notificationPreference = $user->notification_preference?->value ?? 'email';
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(?:\+233|0)\d{9}$/'],
            'notificationPreference' => ['required', Rule::in(array_column(NotificationPreference::cases(), 'value'))],
        ], [
            'phone.regex' => 'Please enter a valid Ghanaian phone number (e.g. 024XXXXXXX or +23324XXXXXXX).',
        ]);

        $user = Auth::user();

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'notification_preference' => $this->notificationPreference,
        ]);

        $customer = $user->customer;
        if ($customer) {
            $customer->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ]);
        }

        $this->dispatch('toast', type: 'success', message: 'Profile updated successfully.');
    }

    public function render(): View
    {
        return view('livewire.customer.profile');
    }
}
