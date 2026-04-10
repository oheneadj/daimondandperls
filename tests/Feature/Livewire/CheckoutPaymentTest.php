<?php

declare(strict_types=1);

use App\Enums\PaymentStatus;
use App\Livewire\Booking\CheckoutPayment;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Package;
use App\Notifications\BookingConfirmedNotification;
use App\Services\MoolrePaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
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

// ── Happy path: no OTP required ──────────────────────────────────────────────

it('skips OTP and enters awaiting state when Moolre returns TR099', function () {
    $this->mock(MoolrePaymentService::class, function ($mock) {
        $mock->shouldReceive('initiatePayment')
            ->once()
            ->andReturn([
                'status' => 1,
                'code' => 'TR099',
                'data' => 'MOOLRE-TEST-123',
                'message' => 'Success',
            ]);
    });

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('momoNetwork', '13')
        ->set('momoNumber', '0541234567')
        ->call('processMobileMoney')
        ->assertHasNoErrors()
        ->assertSet('paymentStep', 'awaiting');

    $booking = $this->booking->fresh();

    expect($booking->payment_status)->toBe(PaymentStatus::Pending)
        ->and($booking->payment_reference)->toBe('MOOLRE-TEST-123');
});

// ── OTP flow ─────────────────────────────────────────────────────────────────

it('enters OTP state when Moolre returns TP14', function () {
    $this->mock(MoolrePaymentService::class, function ($mock) {
        $mock->shouldReceive('initiatePayment')
            ->once()
            ->andReturn([
                'status' => 0,
                'code' => 'TP14',
                'message' => 'Please complete the verification sent via SMS.',
            ]);
    });

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('momoNetwork', '13')
        ->set('momoNumber', '0541234567')
        ->call('processMobileMoney')
        ->assertHasNoErrors()
        ->assertSet('paymentStep', 'otp');

    // Channel + number saved to DB, but payment_status still Unpaid and no reference
    $booking = $this->booking->fresh();

    expect($booking->payment_status)->toBe(PaymentStatus::Unpaid)
        ->and($booking->payment_reference)->toBeNull()
        ->and($booking->payment_channel)->toBe('13')
        ->and($booking->payer_number)->toBe('0541234567');
});

it('restores OTP state on page refresh when channel is set but reference is null', function () {
    $this->booking->update([
        'payment_channel' => '13',
        'payer_number' => '0541234567',
        'payment_status' => 'unpaid',
        'payment_reference' => null,
    ]);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking->fresh()])
        ->assertSet('paymentStep', 'otp')
        ->assertSet('momoNetwork', '13')
        ->assertSet('momoNumber', '0541234567');
});

it('submits OTP and enters awaiting state on TP17 then TR099', function () {
    $this->booking->update([
        'payment_channel' => '13',
        'payer_number' => '0541234567',
    ]);

    $this->mock(MoolrePaymentService::class, function ($mock) {
        $mock->shouldReceive('submitOtp')
            ->once()
            ->andReturn(['status' => 0, 'code' => 'TP17', 'message' => 'OTP verified.']);

        $mock->shouldReceive('initiatePayment')
            ->once()
            ->andReturn(['status' => 1, 'code' => 'TR099', 'data' => 'MOOLRE-REF-999', 'message' => 'Prompt sent.']);
    });

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking->fresh()])
        ->assertSet('paymentStep', 'otp')
        ->set('otpCode', '123456')
        ->call('submitOtp')
        ->assertHasNoErrors()
        ->assertSet('paymentStep', 'awaiting');

    $booking = $this->booking->fresh();

    expect($booking->payment_status)->toBe(PaymentStatus::Pending)
        ->and($booking->payment_reference)->toBe('MOOLRE-REF-999');
});

it('shows error and stays on OTP step for invalid OTP (TP15)', function () {
    $this->booking->update(['payment_channel' => '13', 'payer_number' => '0541234567']);

    $this->mock(MoolrePaymentService::class, function ($mock) {
        $mock->shouldReceive('submitOtp')
            ->once()
            ->andReturn(['status' => 0, 'code' => 'TP15', 'message' => 'Invalid OTP.']);
    });

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking->fresh()])
        ->set('otpCode', '000000')
        ->call('submitOtp')
        ->assertSet('paymentStep', 'otp')
        ->assertSet('errorMessage', 'Invalid verification code. Please check and try again.');
});

it('clears OTP DB state and shows form on cancelPayment', function () {
    $this->booking->update(['payment_channel' => '13', 'payer_number' => '0541234567']);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking->fresh()])
        ->assertSet('paymentStep', 'otp')
        ->call('cancelPayment')
        ->assertSet('paymentStep', 'form');

    $booking = $this->booking->fresh();

    expect($booking->payment_channel)->toBeNull()
        ->and($booking->payer_number)->toBeNull()
        ->and($booking->payment_reference)->toBeNull()
        ->and($booking->payment_status)->toBe(PaymentStatus::Unpaid);
});

// ── Polling resume ────────────────────────────────────────────────────────────

it('resumes awaiting state on refresh when payment_reference is set', function () {
    $this->booking->update([
        'payment_status' => 'pending',
        'payment_reference' => 'MOOLRE-TEST-123',
        'payment_channel' => '13',
        'payer_number' => '0541234567',
    ]);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking->fresh()])
        ->assertSet('paymentStep', 'awaiting')
        ->assertSet('momoNetwork', '13')
        ->assertSet('momoNumber', '0541234567');
});

it('redirects to confirmation and notifies customer when polling detects successful payment', function () {
    Notification::fake();

    $component = Livewire::test(CheckoutPayment::class, ['booking' => $this->booking]);

    $this->booking->update([
        'payment_status' => 'paid',
        'payment_reference' => 'MOOLRE-TEST-123',
        'payment_channel' => '13',
        'payer_number' => '0541234567',
    ]);

    $component
        ->call('checkPaymentStatus')
        ->assertRedirect(route('booking.confirmation', ['booking' => $this->booking->reference]));

    Notification::assertSentTo(
        [$this->customer],
        BookingConfirmedNotification::class,
        fn ($notification) => $notification->booking->id === $this->booking->id
    );
});

// ── Auto-initiate ─────────────────────────────────────────────────────────────

it('sets autoInitiate and pre-populates network and number from session', function () {
    session()->put('checkout_payment_method', [
        'network' => '13',
        'number' => '0541234567',
    ]);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->assertSet('autoInitiate', true)
        ->assertSet('momoNetwork', '13')
        ->assertSet('momoNumber', '0541234567');

    expect(session()->has('checkout_payment_method'))->toBeFalse();
});

// ── Validation ────────────────────────────────────────────────────────────────

it('validates mobile money network and number are required', function () {
    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('momoNetwork', '')
        ->set('momoNumber', '')
        ->call('processMobileMoney')
        ->assertHasErrors(['momoNetwork', 'momoNumber']);
});

it('validates mobile money number matches selected network prefix', function () {
    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('momoNetwork', '13') // MTN
        ->set('momoNumber', '0201234567') // Telecel prefix
        ->call('processMobileMoney')
        ->assertHasErrors(['momoNumber']);
});

it('validates OTP code is required and must be 6 digits', function () {
    $this->booking->update(['payment_channel' => '13', 'payer_number' => '0541234567']);

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking->fresh()])
        ->set('otpCode', '')
        ->call('submitOtp')
        ->assertHasErrors(['otpCode'])
        ->set('otpCode', '123')
        ->call('submitOtp')
        ->assertHasErrors(['otpCode']);
});
