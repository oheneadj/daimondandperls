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

it('dispatches confirmed notification upon successful mobile money payment', function () {
    Notification::fake();

    Livewire::test(CheckoutPayment::class, ['booking' => $this->booking])
        ->assertStatus(200)
        ->call('processMobileMoney')
        ->assertRedirect(route('booking.confirmation', ['booking' => $this->booking->reference]));

    $this->booking->refresh();

    expect($this->booking->status->value)->toBe('confirmed')
        ->and($this->booking->payment_status->value)->toBe('paid');

    Notification::assertSentTo(
        [$this->customer],
        BookingConfirmedNotification::class,
        function ($notification, $channels) {
            return $notification->booking->id === $this->booking->id
                   && in_array('mail', $channels)
                   && in_array(\App\Notifications\Channels\GaintSmsChannel::class, $channels);
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
