<?php

use App\Livewire\Booking\CheckoutPayment;
use App\Models\Booking;
use Livewire\Livewire;

test('displays error message and retry button on failure', function () {
    $booking = Booking::factory()->create(['status' => 'pending', 'payment_status' => 'unpaid']);

    Livewire::test(CheckoutPayment::class, ['booking' => $booking])
        ->assertDontSee('Transaction Failed')
        ->call('simulateMobileMoneyFailure')
        ->assertSee('Transaction Failed')
        ->assertSee('Retry with another method')
        ->assertSet('errorMessage', 'Payment failed! Insufficient funds. Please try again.')
        ->call('retry')
        ->assertSet('errorMessage', null)
        ->assertDontSee('Transaction Failed');
});
