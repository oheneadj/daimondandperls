<?php

declare(strict_types=1);

namespace App\Livewire\Booking;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\EventType;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Services\CartService;
use App\Traits\HandlesBookingCheckout;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Event inquiry wizard: Event Details (1) → Menu Suggestions (2) → Contact (3) → Summary (4)
 */
#[Layout('components.guest-layout')]
class EventInquiryWizard extends Component
{
    use HandlesBookingCheckout;

    // Event Details (Step 1)
    public ?string $event_date = null;

    public ?string $event_start_time = null;

    public ?string $event_end_time = null;

    public ?string $event_type = null;

    public ?string $event_type_other = null;

    // Guest / Service (Step 2)
    public ?int $pax = null;

    public bool $is_buffet = false;

    public ?string $notes = null;

    public function mount(): void
    {
        if ($this->restoreWizardState()) {
            return;
        }

        $this->prefillFromAuth();
    }

    protected function validateCurrentStep(): void
    {
        match ($this->currentStep) {
            1 => $this->validateEventDetails(),
            2 => $this->validateMenuStep(),
            3 => $this->validateContactInfo(),
            default => null,
        };
    }

    private function validateEventDetails(): void
    {
        $this->event_date = $this->event_date ?: null;
        $this->event_start_time = $this->event_start_time ?: null;
        $this->event_end_time = $this->event_end_time ?: null;
        $this->event_type = $this->event_type ?: null;
        $this->event_type_other = $this->event_type_other ?: null;

        $this->validate([
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'event_start_time' => ['nullable', 'required_with:event_date', 'date_format:H:i'],
            'event_end_time' => [
                'nullable',
                'required_with:event_date',
                'date_format:H:i',
                'after:event_start_time',
            ],
            'event_type' => ['nullable', new Enum(EventType::class)],
            'event_type_other' => ['required_if:event_type,other', 'nullable', 'string', 'max:100'],
        ], [
            'event_date.required' => 'Please select an event date.',
            'event_date.after_or_equal' => 'The event date must be today or a future date.',
            'event_end_time.after' => 'The event end time must be after the start time.',
            'event_start_time.required_with' => 'Start time is required when an event date is set.',
            'event_end_time.required_with' => 'End time is required when an event date is set.',
        ]);
    }

    private function validateMenuStep(): void
    {
        $this->validate([
            'pax' => ['nullable', 'integer', 'min:1', 'max:10000'],
        ]);
    }

    public function confirmBooking(CartService $cart): mixed
    {
        $this->loading = 'confirmBooking';
        $this->validateContactInfo();
        $this->validateEventDetails();

        $booking = DB::transaction(function () use ($cart): Booking {
            $customer = $this->resolveCustomer();
            $reference = $this->generateReference();

            $existing = $this->findDuplicateBooking($customer->id, 0);
            if ($existing) {
                return $existing;
            }

            $booking = Booking::create([
                'reference' => $reference,
                'booking_type' => BookingType::Event,
                'customer_id' => $customer->id,
                'event_date' => $this->event_date ?: null,
                'event_start_time' => $this->event_start_time ?: null,
                'event_end_time' => $this->event_end_time ?: null,
                'event_type' => $this->event_type ?: null,
                'event_type_other' => $this->event_type_other ?: null,
                'pax' => $this->pax,
                'is_buffet' => $this->is_buffet,
                'customer_notes' => $this->notes,
                'total_amount' => 0,
                'status' => BookingStatus::Pending,
                'payment_status' => PaymentStatus::Unpaid,
            ]);

            if ($cart->count() > 0) {
                $this->saveCartItemsToBooking($booking, $cart->getCart());
                $cart->clear();
            }

            $this->notifyBookingCreated($booking, $customer);

            return $booking;
        });

        return redirect()->route('booking.confirmation', ['booking' => $booking->reference]);
    }

    protected function getRedirectRoute(): string
    {
        return route('event-booking');
    }

    protected function getWizardSpecificState(): array
    {
        return [
            'event_date' => $this->event_date,
            'event_start_time' => $this->event_start_time,
            'event_end_time' => $this->event_end_time,
            'event_type' => $this->event_type,
            'event_type_other' => $this->event_type_other,
            'pax' => $this->pax,
            'is_buffet' => $this->is_buffet,
            'notes' => $this->notes,
        ];
    }

    protected function restoreWizardSpecificState(array $state): void
    {
        $this->event_date = $state['event_date'] ?? null;
        $this->event_start_time = $state['event_start_time'] ?? null;
        $this->event_end_time = $state['event_end_time'] ?? null;
        $this->event_type = $state['event_type'] ?? null;
        $this->event_type_other = $state['event_type_other'] ?? null;
        $this->pax = $state['pax'] ?? null;
        $this->is_buffet = $state['is_buffet'] ?? false;
        $this->notes = $state['notes'] ?? null;
    }

    #[Title('Plan an Event')]
    public function render(CartService $cart): mixed
    {
        return view('livewire.booking.event-inquiry-wizard', [
            'cartItems' => $cart->getCart(),
            'cartTotal' => 0,
        ]);
    }
}
