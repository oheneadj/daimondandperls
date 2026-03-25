<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Livewire\Admin\Bookings\Show;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('authenticated users can view booking details', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $booking = Booking::factory()->create();

    $response = $this->get(route('admin.bookings.show', $booking));

    $response->assertOk();
    $response->assertSeeLivewire(Show::class);
});

test('unauthenticated users are redirected from booking details', function () {
    $booking = Booking::factory()->create();

    $response = $this->get(route('admin.bookings.show', $booking));

    $response->assertRedirect(route('login'));
});

test('admin can confirm a pending booking', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $booking = Booking::factory()->create([
        'status' => BookingStatus::Pending,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->assertSet('canBeConfirmed', true)
        ->assertSet('canBePrepared', false)
        ->call('promptAction', 'confirmBooking')
        ->assertSet('showActionModal', true)
        ->assertSet('actionToConfirm', 'confirmBooking')
        ->call('executeAction')
        ->assertSet('showActionModal', false)
        ->assertHasNoErrors();

    $booking->refresh();

    expect($booking->status)->toBe(BookingStatus::Confirmed)
        ->and($booking->confirmed_at)->not->toBeNull()
        ->and($booking->confirmed_by)->toBe($user->id);
});

test('admin can start preparation for a confirmed booking', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $booking = Booking::factory()->create([
        'status' => BookingStatus::Confirmed,
        'payment_status' => \App\Enums\PaymentStatus::Paid,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->assertSet('canBePrepared', true)
        ->call('promptAction', 'startPreparation')
        ->assertSet('showActionModal', true)
        ->assertSet('actionToConfirm', 'startPreparation')
        ->call('executeAction')
        ->assertSet('showActionModal', false)
        ->assertHasNoErrors();

    $booking->refresh();

    expect($booking->status)->toBe(BookingStatus::InPreparation);
});

test('admin can mark an in-preparation booking as completed', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $booking = Booking::factory()->create([
        'status' => BookingStatus::InPreparation,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->assertSet('canBeCompleted', true)
        ->call('promptAction', 'completeBooking')
        ->assertSet('showActionModal', true)
        ->assertSet('actionToConfirm', 'completeBooking')
        ->call('executeAction')
        ->assertSet('showActionModal', false)
        ->assertHasNoErrors();

    $booking->refresh();

    expect($booking->status)->toBe(BookingStatus::Completed)
        ->and($booking->completed_at)->not->toBeNull();
});

test('admin can open cancel modal and cancel a booking with a reason', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $booking = Booking::factory()->create([
        'status' => BookingStatus::Pending,
    ]);

    $reason = 'Customer requested cancellation via phone call.';

    Livewire::test(Show::class, ['booking' => $booking])
        ->assertSet('canBeCancelled', true)
        ->call('openCancelModal')
        ->assertSet('showCancelModal', true)
        ->set('cancelReason', $reason)
        ->call('cancelBooking')
        ->assertSet('showCancelModal', false)
        ->assertHasNoErrors();

    $booking->refresh();

    expect($booking->status)->toBe(BookingStatus::Cancelled)
        ->and($booking->cancelled_at)->not->toBeNull()
        ->and($booking->cancelled_reason)->toBe($reason);
});
