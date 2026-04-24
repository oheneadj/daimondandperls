<?php

declare(strict_types=1);

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Livewire\Booking\CheckoutPayment;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerPaymentMethod;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    config([
        'payments.default' => 'transflow',
        'payments.gateways.transflow.base_url' => 'https://transflow.test/checkout',
        'payments.gateways.transflow.api_key' => 'test-api-key',
        'payments.gateways.transflow.transflow_id' => 'test-tf-id',
        'payments.gateways.transflow.merchant_product_id' => 'test-product-id',
    ]);

    $this->customer = Customer::factory()->create();
    $this->category = Category::factory()->create();
    $this->package = Package::factory()->create(['category_id' => $this->category->id, 'price' => 500]);

    $this->booking = Booking::factory()->create([
        'customer_id' => $this->customer->id,
        'total_amount' => 500,
        'status' => 'pending',
        'payment_status' => 'unpaid',
    ]);
});

// ── Mount ─────────────────────────────────────────────────────────────────────

it('mounts in form step with empty paymentChoice for guest', function () {
    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->assertSet('paymentStep', 'form')
        ->assertSet('paymentChoice', '')
        ->assertSet('savedMethods', collect());
});

it('redirects to confirmation if booking is already paid', function () {
    $this->booking->update(['payment_status' => 'paid']);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking->fresh()])
        ->assertRedirect(route('booking.confirmation', ['booking' => $this->booking->reference]));
});

it('shows awaiting step when payment_awaiting session flag is set', function () {
    session()->put('payment_awaiting', true);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->assertSet('paymentStep', 'awaiting');
});

it('resumes awaiting state on refresh when booking is pending with a reference', function () {
    $this->booking->update([
        'payment_status' => 'pending',
        'payment_reference' => 'TXN-TRANSFLOW-123',
    ]);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking->fresh()])
        ->assertSet('paymentStep', 'awaiting');
});

it('defaults to saved method when logged-in user has saved methods', function () {
    $user = User::factory()->create();
    $this->customer->update(['user_id' => $user->id]);

    $method = CustomerPaymentMethod::factory()->create([
        'customer_id' => $this->customer->id,
        'type' => PaymentMethod::MobileMoney->value,
        'provider' => '13',
        'account_number' => '0541234567',
        'label' => 'MTN MoMo - 0541234567',
        'is_default' => true,
        'verified_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->assertSet('paymentChoice', 'saved')
        ->assertSet('selectedMethodId', $method->id);
});

// ── selectPaymentMethod ───────────────────────────────────────────────────────

it('selecting a saved method sets paymentChoice to saved', function () {
    $user = User::factory()->create();
    $this->customer->update(['user_id' => $user->id]);

    $method = CustomerPaymentMethod::factory()->create([
        'customer_id' => $this->customer->id,
        'type' => PaymentMethod::MobileMoney->value,
        'provider' => '13',
        'account_number' => '0541234567',
        'label' => 'MTN MoMo - 0541234567',
        'verified_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->call('selectPaymentMethod', $method->id)
        ->assertSet('paymentChoice', 'saved')
        ->assertSet('selectedMethodId', $method->id);
});

// ── initiateCheckout ──────────────────────────────────────────────────────────

it('initiates checkout and redirects to Transflow URL', function () {
    Http::fake([
        '*/request-payments' => Http::response([
            'responseCode' => 200,
            'data' => [
                'transactionReference' => 'TXN-123',
                'checkoutUrl' => 'https://checkout.transflow.test/pay/TXN-123',
            ],
        ], 200),
    ]);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('paymentChoice', '')
        ->call('initiateCheckout')
        ->assertRedirect('https://checkout.transflow.test/pay/TXN-123');

    expect($this->booking->fresh()->payment_reference)->toBe('TXN-123')
        ->and($this->booking->fresh()->payment_status)->toBe(PaymentStatus::Pending);
});

it('initiates checkout with saved method pre-population', function () {
    $user = User::factory()->create();
    $this->customer->update(['user_id' => $user->id]);

    $method = CustomerPaymentMethod::factory()->create([
        'customer_id' => $this->customer->id,
        'type' => PaymentMethod::MobileMoney->value,
        'provider' => '13',
        'account_number' => '0541234567',
        'label' => 'MTN MoMo - 0541234567',
        'verified_at' => now(),
        'is_default' => true,
    ]);

    $this->actingAs($user);

    Http::fake([
        '*/request-payments' => Http::response([
            'responseCode' => 200,
            'data' => ['transactionReference' => 'TXN-456', 'checkoutUrl' => 'https://checkout.transflow.test/pay/TXN-456'],
        ], 200),
    ]);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('paymentChoice', 'saved')
        ->set('selectedMethodId', $method->id)
        ->call('initiateCheckout')
        ->assertRedirect('https://checkout.transflow.test/pay/TXN-456');

    Http::assertSent(fn ($request) => $request['paymentMethod'] === 'mobile_money' &&
        $request['msisdn'] === '0541234567' &&
        $request['network'] === 'MTN'
    );
});

it('validates new momo number before initiating', function () {
    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('paymentChoice', 'new_momo')
        ->set('momoNetwork', '13')
        ->set('momoNumber', '0201234567') // Telecel prefix on MTN network
        ->call('initiateCheckout')
        ->assertSet('errorMessage', fn ($v) => $v !== null);
});

it('shows error message when Transflow returns a retryable error', function () {
    Http::fake([
        '*/request-payments' => Http::response([
            'responseCode' => 400,
            'responseMessage' => 'Transaction limit exceeded. Please try again.',
        ], 200),
    ]);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('paymentChoice', '')
        ->call('initiateCheckout')
        ->assertSet('errorMessage', 'Transaction limit exceeded. Please try again.');
});

it('shows fatal error for merchant configuration errors', function () {
    Http::fake([
        '*/request-payments' => Http::response([
            'responseCode' => 400,
            'responseMessage' => 'Invalid merchant account setup.',
        ], 200),
    ]);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('paymentChoice', '')
        ->call('initiateCheckout')
        ->assertSet('fatalError', fn ($v) => $v !== null)
        ->assertSet('errorMessage', null);
});

// ── cancelPayment ─────────────────────────────────────────────────────────────

it('cancel payment resets booking and returns to form step', function () {
    $this->booking->update([
        'payment_status' => 'pending',
        'payment_reference' => 'TXN-123',
        'payment_channel' => '13',
        'payer_number' => '0541234567',
    ]);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking->fresh()])
        ->call('cancelPayment')
        ->assertSet('paymentStep', 'form');

    $booking = $this->booking->fresh();

    expect($booking->payment_status)->toBe(PaymentStatus::Unpaid)
        ->and($booking->payment_reference)->toBeNull()
        ->and($booking->payment_channel)->toBeNull()
        ->and($booking->payer_number)->toBeNull();
});

// ── checkPaymentStatus ────────────────────────────────────────────────────────

it('redirects to confirmation when polling detects paid status', function () {
    $component = Livewire::test(CheckoutPayment::class, ['booking' => $this->booking]);

    $this->booking->update(['payment_status' => 'paid', 'payment_reference' => 'TXN-123']);

    $component
        ->call('checkPaymentStatus')
        ->assertRedirect(route('booking.confirmation', ['booking' => $this->booking->reference]));
});

it('shows error and returns to form when polling detects failed status', function () {
    session()->put('payment_awaiting', true);

    $component = Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->assertSet('paymentStep', 'awaiting');

    $this->booking->update(['payment_status' => 'failed']);

    $component
        ->call('checkPaymentStatus')
        ->assertSet('paymentStep', 'form')
        ->assertSet('errorMessage', fn ($v) => str_contains($v, 'declined'));
});
