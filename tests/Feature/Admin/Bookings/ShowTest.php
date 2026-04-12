<?php

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Livewire\Admin\Bookings\Show;
use App\Models\Booking;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $superAdminRole = Role::updateOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin', 'description' => 'Super Administrator']);
    $this->admin = User::factory()->create();
    $this->admin->assignRole($superAdminRole);
    $this->actingAs($this->admin);
});

test('it restricts starting implementation for unpaid bookings', function () {
    $booking = Booking::factory()->create([
        'status' => BookingStatus::Confirmed,
        'payment_status' => PaymentStatus::Unpaid,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->assertSet('booking.status', BookingStatus::Confirmed)
        ->assertSet('booking.payment_status', PaymentStatus::Unpaid)
        ->assertSet('canBePrepared', false)
        ->assertSee('Awaiting Payment');
});

test('it allows starting implementation for paid bookings', function () {
    $booking = Booking::factory()->create([
        'status' => BookingStatus::Confirmed,
        'payment_status' => PaymentStatus::Paid,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->assertSet('canBePrepared', true)
        ->assertDontSee('Awaiting Payment');
});

test('it allows manual payment verification', function () {
    $booking = Booking::factory()->create([
        'status' => BookingStatus::Confirmed,
        'payment_status' => PaymentStatus::Unpaid,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->assertSet('canBeVerified', true)
        ->set('verificationNotes', 'Paid in cash')
        ->call('verifyPayment')
        ->assertSet('booking.payment_status', PaymentStatus::Paid)
        ->assertSet('canBePrepared', true)
        ->assertDispatched('notify');

    $booking->refresh();
    expect($booking->payment_status)->toBe(PaymentStatus::Paid);
    expect($booking->payment)->not->toBeNull();
    expect($booking->payment->verified_by)->toBe($this->admin->id);
    expect($booking->payment->gateway_response)->toBe(['notes' => 'Paid in cash']);
});
