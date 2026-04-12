<?php

declare(strict_types=1);

namespace App\Livewire\Booking;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\CustomerPaymentMethod;
use App\Models\Setting;
use App\Services\CartService;
use App\Traits\HandlesBookingCheckout;
use App\Traits\HandlesMomoValidation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
    use HandlesMomoValidation;

    // Delivery location
    public ?string $deliveryLocation = null;

    // Payment fields
    public string $momoNetwork = '';

    public string $momoNumber = '';

    /** @var Collection<int, CustomerPaymentMethod> */
    public Collection $savedMethods;

    public ?int $selectedMethodId = null;

    public bool $useNewNumber = false;

    public function mount(): void
    {
        $this->savedMethods = collect();

        if (app(CartService::class)->count() === 0) {
            $this->redirect(route('home'));

            return;
        }

        if ($this->restoreWizardState()) {
            $this->loadSavedMethods();

            return;
        }

        $this->prefillFromAuth();
        $this->loadSavedMethods();
    }

    public function updatedMomoNumber(string $value): void
    {
        $this->momoNumber = preg_replace('/[^0-9]/', '', $value);
    }

    public function getIsMomoFormValidProperty(): bool
    {
        return $this->isValidMomoNumber($this->momoNetwork, $this->momoNumber);
    }

    #[Computed]
    public function isReadyToConfirm(): bool
    {
        // Contact info
        if (empty(trim((string) $this->name))) {
            return false;
        }

        if (empty($this->phone) || ! preg_match('/^(?:\+233|0)\d{9}$/', $this->phone)) {
            return false;
        }

        // Delivery location (only required when locations are configured)
        $locations = Setting::where('key', 'delivery_locations')->first()?->value ?? [];

        if (! empty($locations) && ! \in_array($this->deliveryLocation, $locations)) {
            return false;
        }

        // Payment
        if (! \in_array($this->momoNetwork, ['13', '6', '7'])) {
            return false;
        }

        if (! $this->getIsMomoFormValidProperty()) {
            return false;
        }

        return true;
    }

    public function selectPaymentMethod(int $id): void
    {
        $method = $this->savedMethods->firstWhere('id', $id);

        if ($method) {
            $this->selectedMethodId = $id;
            $this->useNewNumber = false;
            $this->momoNetwork = $method->provider;
            $this->momoNumber = $method->account_number;
        }
    }

    public function useNewPaymentMethod(): void
    {
        $this->selectedMethodId = null;
        $this->useNewNumber = true;
        $this->momoNetwork = '';
        $this->momoNumber = '';
    }

    protected function validateCurrentStep(): void
    {
        $this->validateDeliveryLocation();
        $this->validateContactInfo();
        $this->validatePayment();
    }

    public function confirmBooking(CartService $cart): mixed
    {
        $this->loading = 'confirmBooking';
        $this->validateDeliveryLocation();
        $this->validateContactInfo();
        $this->validatePayment();

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

        session()->put('checkout_payment_method', [
            'network' => $this->momoNetwork,
            'number' => $this->momoNumber,
        ]);

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
            'momoNetwork' => $this->momoNetwork,
            'momoNumber' => $this->momoNumber,
            'selectedMethodId' => $this->selectedMethodId,
            'useNewNumber' => $this->useNewNumber,
        ];
    }

    protected function restoreWizardSpecificState(array $state): void
    {
        $this->deliveryLocation = $state['deliveryLocation'] ?? null;
        $this->momoNetwork = $state['momoNetwork'] ?? '';
        $this->momoNumber = $state['momoNumber'] ?? '';
        $this->selectedMethodId = $state['selectedMethodId'] ?? null;
        $this->useNewNumber = $state['useNewNumber'] ?? false;
    }

    #[Title('Checkout')]
    public function render(CartService $cart): mixed
    {
        if ($cart->count() === 0) {
            $this->redirect(route('home'));
        }

        $deliveryLocations = Setting::where('key', 'delivery_locations')->first()?->value ?? [];

        return view('livewire.booking.booking-wizard', [
            'cartItems' => $cart->getCart(),
            'cartTotal' => $cart->getTotal(),
            'deliveryLocations' => $deliveryLocations,
        ]);
    }

    private function loadSavedMethods(): void
    {
        if (! Auth::check()) {
            $this->useNewNumber = true;

            return;
        }

        $customer = Auth::user()->customer;

        if ($customer) {
            $this->savedMethods = $customer->paymentMethods()
                ->where('type', PaymentMethod::MobileMoney->value)
                ->whereNotNull('verified_at')
                ->orderByDesc('is_default')
                ->orderBy('created_at')
                ->get();
        }

        if ($this->savedMethods->isNotEmpty() && empty($this->momoNetwork)) {
            $default = $this->savedMethods->firstWhere('is_default', true) ?? $this->savedMethods->first();
            $this->selectedMethodId = $default->id;
            $this->momoNetwork = $default->provider;
            $this->momoNumber = $default->account_number;
        } elseif ($this->savedMethods->isEmpty()) {
            $this->useNewNumber = true;
        }
    }

    private function validateDeliveryLocation(): void
    {
        $locations = Setting::where('key', 'delivery_locations')->first()?->value ?? [];

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

    private function validatePayment(): void
    {
        $networkPrefixPattern = $this->getNetworkPrefixPattern($this->momoNetwork);

        $this->validate([
            'momoNetwork' => 'required|in:13,6,7',
            'momoNumber' => ['required', 'regex:'.$networkPrefixPattern],
        ], [
            'momoNetwork.required' => 'Please select your mobile network.',
            'momoNetwork.in' => 'Invalid network selected.',
            'momoNumber.required' => 'Please provide your mobile money number.',
            'momoNumber.regex' => "This number doesn't match the selected network.",
        ]);
    }
}
