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

    $this->booking->refresh();

    expect($this->booking->payment_status->value)->toBe('pending')
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

it('dispatches confirmed notification upon successful card payment', function () {
    Notification::fake();

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->set('paymentMethod', 'card')
        ->assertStatus(200)
        ->call('processCard')
        ->assertRedirect(route('booking.confirmation', ['booking' => $this->booking->reference]));

    $this->booking->refresh();

    expect($this->booking->status->value)->toBe('confirmed')
        ->and($this->booking->payment_status->value)->toBe('paid');

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
        ->call('submitBankTransfer')
        ->assertRedirect(route('booking.confirmation', ['booking' => $this->booking->reference]));

    $this->booking->refresh();

    expect($this->booking->status->value)->toBe('pending')
        ->and($this->booking->payment_status->value)->toBe('unpaid');

    Notification::assertNothingSent();
});
