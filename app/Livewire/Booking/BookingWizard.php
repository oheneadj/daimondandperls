<?php

declare(strict_types=1);

namespace App\Livewire\Booking;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Services\CartService;
use App\Services\LoyaltyService;
use App\Traits\HandlesBookingCheckout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public ?string $deliveryLocation = null;

    public bool $usePoints = false;

    public int $pointsToRedeem = 0;

    public float $pointsDiscount = 0.0;

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

    /**
     * @return array{balance: int, balance_ghc: float, max_discount_ghc: float, max_points: int}|null
     */
    #[Computed]
    public function loyaltyData(CartService $cart): ?array
    {
        $loyalty = app(LoyaltyService::class);

        if (! $loyalty->isEnabled() || ! Auth::check()) {
            return null;
        }

        $customer = Auth::user()->customer;

        if (! $customer || $customer->loyalty_points <= 0) {
            return null;
        }

        return $loyalty->getRedeemablePoints($customer, $cart->getTotal());
    }

    public function applyPoints(CartService $cart): void
    {
        if (! Auth::check()) {
            return;
        }

        $customer = Auth::user()->customer;

        if (! $customer) {
            return;
        }

        $loyalty = app(LoyaltyService::class);
        $data = $loyalty->getRedeemablePoints($customer, $cart->getTotal());

        $this->pointsToRedeem = $data['max_points'];
        $this->pointsDiscount = $data['max_discount_ghc'];
        $this->usePoints = true;
    }

    public function removePoints(): void
    {
        $this->usePoints = false;
        $this->pointsToRedeem = 0;
        $this->pointsDiscount = 0.0;
    }

    #[Computed]
    public function isReadyToConfirm(): bool
    {

        if (empty(trim((string) $this->name))) {
            return false;
        }

        if (empty($this->phone) || ! preg_match('/^(?:\+233|0)\d{9}$/', $this->phone)) {
            // if what the user entere isnt 0-9 clear the input

            return false;
        }

        if (empty(trim((string) $this->email))) {
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

        $discount = $this->usePoints ? (float) $this->pointsDiscount : 0.0;

        $booking = DB::transaction(function () use ($cart, $discount): Booking {
            $customer = $this->resolveCustomer();
            $reference = $this->generateReference();
            $totalAmount = max(0, $cart->getTotal() - $discount);

            $existing = $this->findDuplicateBooking($customer->id, $totalAmount);
            if ($existing) {
                return $existing;
            }

            $booking = Booking::create([
                'reference' => $reference,
                'booking_type' => BookingType::Meal,
                'customer_id' => $customer->id,
                'total_amount' => $totalAmount,
                'discount_amount' => $discount,
                'status' => BookingStatus::Pending,
                'payment_status' => PaymentStatus::Unpaid,
                'delivery_location' => $this->deliveryLocation,
            ]);

            $this->saveCartItemsToBooking($booking, $cart->getCart());
            $this->notifyBookingCreated($booking, $customer);

            return $booking;
        });

        if ($discount > 0) {
            try {
                app(LoyaltyService::class)->confirmRedemption($booking);
            } catch (\Throwable $e) {
                Log::error('LoyaltyService: redemption failed', [
                    'booking' => $booking->reference,
                    'exception' => $e->getMessage(),
                ]);
            }
        }

        $this->saveWizardState();

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
            'usePoints' => $this->usePoints,
            'pointsToRedeem' => $this->pointsToRedeem,
            'pointsDiscount' => $this->pointsDiscount,
        ];
    }

    protected function restoreWizardSpecificState(array $state): void
    {
        $this->deliveryLocation = $state['deliveryLocation'] ?? null;
        $this->usePoints = $state['usePoints'] ?? false;
        $this->pointsToRedeem = $state['pointsToRedeem'] ?? 0;
        $this->pointsDiscount = $state['pointsDiscount'] ?? 0.0;
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
            'loyaltyData' => $this->loyaltyData($cart),
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
