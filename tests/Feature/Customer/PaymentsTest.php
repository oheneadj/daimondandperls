<?php

use App\Enums\PaymentGatewayStatus;
use App\Livewire\Customer\Payments\Index;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('customer can view payments index', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    $this->actingAs($user)
        ->get(route('dashboard.payments.index'))
        ->assertOk();
});

test('customer sees their own payments', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    $booking = Booking::factory()->create(['customer_id' => $customer->id]);
    $payment = Payment::factory()->create([
        'booking_id' => $booking->id,
        'amount' => 250.00,
        'status' => PaymentGatewayStatus::Successful,
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertSee($booking->reference)
        ->assertSee('250.00');
});

test('customer does not see other customers payments', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    $otherCustomer = Customer::factory()->create();
    $otherBooking = Booking::factory()->create(['customer_id' => $otherCustomer->id]);
    Payment::factory()->create([
        'booking_id' => $otherBooking->id,
        'amount' => 999.00,
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertDontSee('999.00');
});
