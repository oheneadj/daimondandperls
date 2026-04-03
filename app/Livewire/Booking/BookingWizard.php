<?php

declare(strict_types=1);

namespace App\Livewire\Booking;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Services\CartService;
use App\Traits\HandlesBookingCheckout;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Meal booking wizard: Review Cart (1) → Contact (2) → Summary (3)
 */
#[Layout('components.guest-layout')]
class BookingWizard extends Component
{
    use HandlesBookingCheckout;

    public function mount(): void
    {
        if (app(CartService::class)->count() === 0) {
            $this->redirect(route('home'));

            return;
        }

        if ($this->restoreWizardState()) {
            return;
        }

        $this->prefillFromAuth();
    }

    protected function validateCurrentStep(): void
    {
        match ($this->currentStep) {
            1 => null, // Review step — no validation needed
            2 => $this->validateContactInfo(),
            default => null,
        };
    }

    public function confirmBooking(CartService $cart): mixed
    {
        $this->loading = 'confirmBooking';
        $this->validateContactInfo();

        $booking = DB::transaction(function () use ($cart): Booking {
            $customer = $this->resolveCustomer();
            $reference = $this->generateReference();
            $totalAmount = $cart->getTotal();

            $existing = $this->findDuplicateBooking($customer->id, $totalAmount);
            if ($existing) {
                return $existing;
            }

            $booking = Booking::create([
                'reference' => $reference,
                'booking_type' => BookingType::Meal,
                'customer_id' => $customer->id,
                'total_amount' => $totalAmount,
                'status' => BookingStatus::Pending,
                'payment_status' => PaymentStatus::Unpaid,
            ]);

            $this->saveCartItemsToBooking($booking, $cart->getCart());
            $cart->clear();
            $this->notifyBookingCreated($booking, $customer);

            return $booking;
        });

        return redirect()->route('booking.payment', ['booking' => $booking->reference]);
    }

    protected function getRedirectRoute(): string
    {
        return route('checkout');
    }

    #[Title('Checkout')]
    public function render(CartService $cart): mixed
    {
        if ($cart->count() === 0) {
            $this->redirect(route('home'));
        }

        return view('livewire.booking.booking-wizard', [
            'cartItems' => $cart->getCart(),
            'cartTotal' => $cart->getTotal(),
        ]);
    }
}
