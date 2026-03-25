<?php

namespace App\Livewire\Booking;

use App\Models\Booking;
use App\Models\Customer;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.guest-layout')]
class BookingWizard extends Component
{
    public int $currentStep = 1;

    public ?string $loading = null;

    // Contact Information (Step 1)
    public ?string $name = null;

    public ?string $phone = null;

    public ?string $email = null;
    
    public bool $createAccount = false;
    
    public ?string $password = null;

    // Event Details (Step 2)
    public ?string $event_date = null;

    public ?string $event_start_time = null;

    public ?string $event_end_time = null;

    public ?string $event_type = null;

    public ?string $event_type_other = null;

    public function mount()
    {
        if (app(CartService::class)->count() === 0) {
            return redirect()->route('home');
        }

        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
        }
    }

    public function updated($propertyName)
    {
        if ($this->$propertyName === '') {
            $this->$propertyName = null;
        }
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            // No specific validation needed for selection review, just move on
        } elseif ($this->currentStep === 2) {
            $this->validateStep2();
        } elseif ($this->currentStep === 3) {
            $this->validateStep3();
        }

        $this->currentStep++;
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    private function validateStep2()
    {
        $rules = [
            'name' => ['required', 'string', 'max:100', 'min:3'],
            'phone' => ['required', 'regex:/^(?:\+233|0)\d{9}$/'],
            'email' => ['nullable', 'email', 'max:150'],
        ];

        if ($this->createAccount) {
            $rules['email'] = ['required', 'email', 'max:150', 'unique:users,email'];
            $rules['password'] = ['required', 'string', 'min:8'];
        }

        $this->validate($rules, [
            'phone.regex' => 'Please enter a valid Ghanaian phone number (e.g. 024XXXXXXX or +23324XXXXXXX).',
            'email.unique' => 'This email is already registered. Please login instead.',
        ]);
    }

    private function validateStep3()
    {
        // Treat empty strings as null so that 'nullable' rules work as expected
        $this->event_date = $this->event_date ?: null;
        $this->event_start_time = $this->event_start_time ?: null;
        $this->event_end_time = $this->event_end_time ?: null;
        $this->event_type = $this->event_type ?: null;
        $this->event_type_other = $this->event_type_other ?: null;

        $this->validate([
            'event_date' => ['nullable', 'date', 'after_or_equal:today'],
            'event_start_time' => ['nullable', 'date_format:H:i'],
            'event_end_time' => [
                'nullable',
                'date_format:H:i',
                'after:event_start_time',
            ],
            'event_type' => ['nullable', \Illuminate\Validation\Rules\Enum::class => new \Illuminate\Validation\Rules\Enum(\App\Enums\EventType::class)],
            'event_type_other' => ['required_if:event_type,other', 'nullable', 'string', 'max:100'],
        ], [
            'event_date.after_or_equal' => 'The event date must be today or a future date.',
            'event_end_time.after' => 'The event end time must be after the start time.',
        ]);
    }

    public function confirmBooking(CartService $cart)
    {
        $this->loading = 'confirmBooking';
        $this->validateStep2();
        $this->validateStep3();

        $booking = DB::transaction(function () use ($cart) {
            // Find or create customer
            $customer = Customer::query()->where(['phone' => $this->phone])->first();

            if ($this->createAccount && ! \Illuminate\Support\Facades\Auth::check()) {
                $user = \App\Models\User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => \Illuminate\Support\Facades\Hash::make($this->password),
                    'phone' => $this->phone,
                    'type' => \App\Enums\UserType::Customer,
                ]);

                if ($customer) {
                    $customer->update([
                        'user_id' => $user->id,
                        'name' => $this->name,
                        'email' => $this->email,
                    ]);
                } else {
                    $customer = $user->customer()->create([
                        'name' => $this->name,
                        'phone' => $this->phone,
                        'email' => $this->email,
                    ]);
                }

                \Illuminate\Support\Facades\Auth::login($user);
            } elseif (!$customer) {
                $customer = Customer::query()->create([
                    'phone' => $this->phone,
                    'name' => $this->name,
                    'email' => $this->email,
                ]);
            } else {
                // Update existing customer info if not logged in or if info changed
                $customer->update([
                    'name' => $this->name,
                    'email' => $this->email,
                ]);
            }

            // Generate unique reference
            $reference = $this->generateReference();

            // Check for existing pending booking with same amount created recently (15m)
            $existingBooking = Booking::where('customer_id', $customer->id)
                ->where('status', \App\Enums\BookingStatus::Pending)
                ->where('payment_status', \App\Enums\PaymentStatus::Unpaid)
                ->where('total_amount', $cart->getTotal())
                ->where('created_at', '>=', now()->subMinutes(15))
                ->first();

            if ($existingBooking) {
                return $existingBooking;
            }

            // Create booking
            $booking = Booking::create([
                'reference' => $reference,
                'customer_id' => $customer->id,
                'event_date' => $this->event_date ?: null,
                'event_start_time' => $this->event_start_time ?: null,
                'event_end_time' => $this->event_end_time ?: null,
                'event_type' => $this->event_type ?: null,
                'event_type_other' => $this->event_type_other ?: null,
                'total_amount' => $cart->getTotal(),
                'status' => \App\Enums\BookingStatus::Pending,
                'payment_status' => \App\Enums\PaymentStatus::Unpaid,
            ]);

            // Save Cart Items
            foreach ($cart->getCart() as $item) {
                $booking->items()->create([
                    'package_id' => $item['package']->id,
                    'price' => $item['package']->price,
                    'quantity' => $item['quantity'],
                ]);
            }

            $cart->clear();

            $admins = \App\Models\User::query()
                ->whereIn('role', [\App\Enums\UserRole::Admin, \App\Enums\UserRole::SuperAdmin])
                ->where(['is_active' => true])
                ->get();

            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\BookingReceivedNotification($booking));

            // Notify Customer
            $customer->notify(new \App\Notifications\CustomerBookingReceivedNotification($booking));

            return $booking;
        });

        return redirect()->route('booking.payment', ['booking' => $booking->reference]);
    }

    private function generateReference(): string
    {
        $year = date('Y');

        // Find the latest booking for the current year
        $latestBooking = Booking::where('reference', 'like', "CAT-{$year}-%")->orderBy('id', 'desc')->first();

        $sequence = 1;
        if ($latestBooking) {
            $parts = explode('-', $latestBooking->reference);
            if (count($parts) === 3) {
                $sequence = intval($parts[2]) + 1;
            }
        }

        do {
            $reference = sprintf('CAT-%s-%05d', $year, $sequence);
            // double check uniqueness
            $exists = Booking::where('reference', $reference)->exists();
            if ($exists) {
                $sequence++;
            }
        } while ($exists);

        return $reference;
    }

    #[Title('Checkout')]
    public function render(CartService $cart)
    {
        // Redirect if cart is empty
        if ($cart->count() === 0) {
            return redirect()->route('home');
        }

        return view('livewire.booking.booking-wizard', [
            'cartItems' => $cart->getCart(),
            'cartTotal' => $cart->getTotal(),
        ]);
    }
}
