<?php

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Livewire\Customer\Bookings\Index;
use App\Livewire\Customer\Bookings\Show;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('customer can view bookings index', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    Booking::factory()->count(3)->create(['customer_id' => $customer->id]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertOk();
});

test('customer only sees their own bookings', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);
    $otherCustomer = Customer::factory()->create();

    $ownBooking = Booking::factory()->create(['customer_id' => $customer->id]);
    $otherBooking = Booking::factory()->create(['customer_id' => $otherCustomer->id]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertSee($ownBooking->reference)
        ->assertDontSee($otherBooking->reference);
});

test('bookings can be filtered by status', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    $pending = Booking::factory()->create([
        'customer_id' => $customer->id,
        'status' => BookingStatus::Pending,
    ]);
    $completed = Booking::factory()->create([
        'customer_id' => $customer->id,
        'status' => BookingStatus::Completed,
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->set('status', BookingStatus::Pending->value)
        ->assertSee($pending->reference)
        ->assertDontSee($completed->reference);
});

test('bookings can be filtered by payment status', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    $unpaid = Booking::factory()->create([
        'customer_id' => $customer->id,
        'payment_status' => PaymentStatus::Unpaid,
    ]);
    $paid = Booking::factory()->create([
        'customer_id' => $customer->id,
        'payment_status' => PaymentStatus::Paid,
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->set('paymentStatus', PaymentStatus::Unpaid->value)
        ->assertSee($unpaid->reference)
        ->assertDontSee($paid->reference);
});

test('bookings can be searched by reference', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    $target = Booking::factory()->create([
        'customer_id' => $customer->id,
        'reference' => 'CAT-2026-00099',
    ]);
    $other = Booking::factory()->create([
        'customer_id' => $customer->id,
        'reference' => 'CAT-2026-00001',
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->set('search', '00099')
        ->assertSee($target->reference)
        ->assertDontSee($other->reference);
});

test('customer can view their own booking details', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);
    $booking = Booking::factory()->create(['customer_id' => $customer->id]);

    Livewire::actingAs($user)
        ->test(Show::class, ['booking' => $booking])
        ->assertOk()
        ->assertSee($booking->reference);
});

test('customer cannot view another customers booking', function () {
    $user = User::factory()->customer()->create();
    Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    $otherCustomer = Customer::factory()->create();
    $otherBooking = Booking::factory()->create(['customer_id' => $otherCustomer->id]);

    Livewire::actingAs($user)
        ->test(Show::class, ['booking' => $otherBooking])
        ->assertForbidden();
});
