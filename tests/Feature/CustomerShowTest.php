<?php

use App\Livewire\Customers\CustomerShow;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('customer show page displays customer details and booking history', function () {
    $customer = Customer::factory()->create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '0244000000',
    ]);

    $booking = Booking::factory()->create([
        'customer_id' => $customer->id,
        'total_amount' => 500,
        'status' => 'confirmed',
        'payment_status' => 'paid',
    ]);

    Payment::factory()->create([
        'booking_id' => $booking->id,
        'status' => 'successful',
        'amount' => 500,
    ]);

    Livewire::test(CustomerShow::class, ['customer' => $customer])
        ->assertSee('Jane Doe')
        ->assertSee('jane@example.com')
        ->assertSee('0244000000')
        ->assertSee('GH₵500.00') // LTV
        ->assertSee($booking->reference);
});

test('customer show page handles no bookings', function () {
    $customer = Customer::factory()->create(['name' => 'Empty Customer']);

    Livewire::test(CustomerShow::class, ['customer' => $customer])
        ->assertSee('Empty Customer')
        ->assertSee('No history found matching your criteria.')
        ->assertSee('GH₵0'); // LTV
});
