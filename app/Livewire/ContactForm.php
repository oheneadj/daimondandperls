<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\UserType;
use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\ContactMessageReceivedNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $phone = '';

    public string $email = '';

    public string $inquiry_type = 'General Inquiry';

    public string $message = '';

    public bool $submitted = false;

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^(?:\+233|0)\d{9}$/'],
            'email' => ['required', 'email', 'max:255'],
            'inquiry_type' => ['required', 'string'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name.',
            'phone.required' => 'Please enter your phone number.',
            'phone.regex' => 'Please enter a valid Ghana phone number (e.g. 0244000000 or +233244000000).',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'message.required' => 'Please enter your message.',
            'message.min' => 'Your message should be at least 10 characters.',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        try {
            $contactMessage = ContactMessage::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'inquiry_type' => $this->inquiry_type,
                'message' => $this->message,
                'ip_address' => request()->ip(),
            ]);

            User::query()
                ->where('type', UserType::Admin)
                ->where('is_active', true)
                ->get()
                ->filter(fn (User $admin): bool => $admin->hasPermission('receive_contact_notifications'))
                ->each(fn (User $admin) => $admin->notify(new ContactMessageReceivedNotification($contactMessage)));
        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'name' => $this->name,
                'email' => $this->email,
            ]);
        }

        $this->reset(['name', 'phone', 'email', 'inquiry_type', 'message']);
        $this->inquiry_type = 'General Inquiry';
        $this->submitted = true;
    }

    public function render(): View
    {
        return view('livewire.contact-form');
    }
}
