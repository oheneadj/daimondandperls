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
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Meal booking: single-screen contact + payment + confirm → payment
 */
#[Layout('components.guest-layout')]
class BookingWizard extends Component
{
    use HandlesBookingCheckout;

    // Delivery location
    public ?string $deliveryLocation = null;

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

    #[Computed]
    public function isReadyToConfirm(): bool
    {
        if (empty(trim((string) $this->name))) {
            return false;
        }

        if (empty($this->phone) || ! preg_match('/^(?:\+233|0)\d{9}$/', $this->phone)) {
            return false;
        }

        $locations = dpc_setting('delivery_locations', []);

        if (! empty($locations) && ! \in_array($this->deliveryLocation, $locations)) {
            return false;
        }

        return true;
    }

    protected function validateCurrentStep(): void
    {
        $this->validateDeliveryLocation();
        $this->validateContactInfo();
    }

    public function confirmBooking(CartService $cart): mixed
    {
        $this->loading = 'confirmBooking';
        $this->validateDeliveryLocation();
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
                'delivery_location' => $this->deliveryLocation,
            ]);

            $this->saveCartItemsToBooking($booking, $cart->getCart());
            $this->notifyBookingCreated($booking, $customer);

            return $booking;
        });

        return redirect()->route('booking.payment', ['booking' => $booking->reference]);
    }

    protected function getContactStepNumber(): int
    {
        return 1;
    }

    protected function getRedirectRoute(): string
    {
        return route('checkout');
    }

    protected function getWizardSpecificState(): array
    {
        return [
            'deliveryLocation' => $this->deliveryLocation,
        ];
    }

    protected function restoreWizardSpecificState(array $state): void
    {
        $this->deliveryLocation = $state['deliveryLocation'] ?? null;
    }

    #[Title('Checkout')]
    public function render(CartService $cart): mixed
    {
        if ($cart->count() === 0) {
            $this->redirect(route('home'));
        }

        $deliveryLocations = dpc_setting('delivery_locations', []);

        return view('livewire.booking.booking-wizard', [
            'cartItems' => $cart->getCart(),
            'cartTotal' => $cart->getTotal(),
            'deliveryLocations' => $deliveryLocations,
        ]);
    }

    private function validateDeliveryLocation(): void
    {
        $locations = dpc_setting('delivery_locations', []);

        if (empty($locations)) {
            return;
        }

        $this->validate([
            'deliveryLocation' => ['required', 'string', 'in:'.implode(',', $locations)],
        ], [
            'deliveryLocation.required' => 'Please select a delivery location.',
            'deliveryLocation.in' => 'Please select a valid delivery location.',
        ]);
    }
}
