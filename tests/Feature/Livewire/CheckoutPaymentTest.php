<?php

use App\Livewire\Booking\CheckoutPayment;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Package;
use App\Notifications\BookingConfirmedNotification;
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

it('initiates mobile money payment via Moolre and enters awaiting state', function () {
    $this->mock(\App\Services\MoolrePaymentService::class, function ($mock) {
        $mock->shouldReceive('initiatePayment')
            ->once()
            ->andReturn([
                'status' => 1,
                'data' => 'MOOLRE-TEST-123',
                'message' => 'Success',
            ]);
    });

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('momoNetwork', '13')
        ->set('momoNumber', '0541234567')
        ->assertStatus(200)
        ->call('processMobileMoney')
        ->assertHasNoErrors()
        ->assertSet('isAwaitingPayment', true);

    $this->booking = $this->booking->fresh();

    expect($this->booking->payment_status)->toBe(\App\Enums\PaymentStatus::Pending)
        ->and($this->booking->payment_reference)->toBe('MOOLRE-TEST-123');
});

it('redirects to confirmation and notifies customer when polling detects successful payment', function () {
    Notification::fake();

    $component = Livewire::test(CheckoutPayment::class, ['booking' => $this->booking]);

    // Simulate webhook already marked it as paid after component mounts
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
        function ($notification, $channels) {
            return $notification->booking->id === $this->booking->id;
        }
    );
});

it('does not dispatch notification for bank transfer', function () {
    Notification::fake();

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('paymentMethod', 'bank_transfer')
        ->set('senderName', 'John Doe Transfer')
        ->set('senderPhone', '0241234567')
        ->call('submitBankTransfer')
        ->assertRedirect(route('booking.confirmation', ['booking' => $this->booking->reference]));

    $this->booking->refresh();

    expect($this->booking->status->value)->toBe('pending')
        ->and($this->booking->payment_status->value)->toBe('pending');

    Notification::assertNothingSent();
});

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

it('validates bank transfer requires sender name and phone', function () {
    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('paymentMethod', 'bank_transfer')
        ->set('senderName', '')
        ->set('senderPhone', '')
        ->call('submitBankTransfer')
        ->assertHasErrors(['senderName', 'senderPhone']);
});

it('validates bank transfer phone format', function () {
    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('paymentMethod', 'bank_transfer')
        ->set('senderName', 'John Doe')
        ->set('senderPhone', '12345')
        ->call('submitBankTransfer')
        ->assertHasErrors(['senderPhone']);
});

it('accepts valid Ghana phone formats for bank transfer', function () {
    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('paymentMethod', 'bank_transfer')
        ->set('senderName', 'John Doe')
        ->set('senderPhone', '0241234567')
        ->call('submitBankTransfer')
        ->assertHasNoErrors(['senderPhone'])
        ->assertRedirect();
});
