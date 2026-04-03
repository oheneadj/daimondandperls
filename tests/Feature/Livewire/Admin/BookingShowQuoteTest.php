<?php

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserType;
use App\Livewire\Admin\Bookings\Show;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\QuoteUpdatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create([
        'type' => UserType::Admin,
        'is_active' => true,
    ]);
    $this->actingAs($this->admin);
});

it('shows update event details button for pending event bookings', function () {
    $booking = Booking::factory()->event()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->assertSee('Update Event Details');
});

it('shows update event details button for confirmed unpaid event bookings', function () {
    $booking = Booking::factory()->event()->create([
        'status' => BookingStatus::Confirmed,
        'payment_status' => PaymentStatus::Unpaid,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->assertSee('Update Event Details');
});

it('hides update event details button for meal bookings', function () {
    $booking = Booking::factory()->meal()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->assertSet('canEditEvent', false);
});

it('hides update event details button for paid event bookings', function () {
    $booking = Booking::factory()->event()->create([
        'status' => BookingStatus::Confirmed,
        'payment_status' => PaymentStatus::Paid,
        'total_amount' => 500,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->assertSet('canEditEvent', false);
});

it('can update quote amount for event booking', function () {
    Notification::fake();

    $booking = Booking::factory()->event()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
        'total_amount' => 0,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->call('openQuoteModal')
        ->assertSet('showQuoteModal', true)
        ->set('quoteAmount', '750.00')
        ->call('updateQuote')
        ->assertSet('showQuoteModal', false)
        ->assertDispatched('banner');

    $booking->refresh();
    expect((float) $booking->total_amount)->toEqual(750.0);
});

it('validates quote amount is required and positive', function () {
    $booking = Booking::factory()->event()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->call('openQuoteModal')
        ->set('quoteAmount', '')
        ->call('updateQuote')
        ->assertHasErrors(['quoteAmount']);

    Livewire::test(Show::class, ['booking' => $booking])
        ->call('openQuoteModal')
        ->set('quoteAmount', '0')
        ->call('updateQuote')
        ->assertHasErrors(['quoteAmount']);
});

it('sends notification to customer when quote is set', function () {
    Notification::fake();

    $booking = Booking::factory()->event()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
        'total_amount' => 0,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->call('openQuoteModal')
        ->set('quoteAmount', '500.00')
        ->call('updateQuote');

    Notification::assertSentTo(
        $booking->customer,
        QuoteUpdatedNotification::class
    );
});

it('shows quote pending in invoice footer for zero-amount event bookings', function () {
    $booking = Booking::factory()->event()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
        'total_amount' => 0,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->assertSee('Quote Pending');
});

it('shows booking type badge', function () {
    $booking = Booking::factory()->event()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->assertSee('event');
});

it('pre-fills current amount when opening quote modal', function () {
    $booking = Booking::factory()->event()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
        'total_amount' => 300,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->call('openQuoteModal')
        ->assertSet('quoteAmount', '300.00');
});

it('can update event details with password confirmation', function () {
    Notification::fake();

    $booking = Booking::factory()->event()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
        'total_amount' => 0,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->call('openEventEditModal')
        ->assertSet('showEventEditModal', true)
        ->set('editEventDate', '2026-05-15')
        ->set('editEventType', 'wedding')
        ->set('editPax', 150)
        ->set('editIsBuffet', true)
        ->set('editQuoteAmount', '1200.00')
        ->set('confirmPassword', 'password')
        ->call('updateEventDetails')
        ->assertSet('showEventEditModal', false)
        ->assertDispatched('banner');

    $booking->refresh();
    expect((float) $booking->total_amount)->toEqual(1200.0)
        ->and($booking->event_date->format('Y-m-d'))->toEqual('2026-05-15')
        ->and($booking->event_type->value)->toEqual('wedding')
        ->and($booking->pax)->toEqual(150);

    Notification::assertSentTo(
        $booking->customer,
        QuoteUpdatedNotification::class
    );
});

it('rejects wrong password when updating event details', function () {
    $booking = Booking::factory()->event()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
        'total_amount' => 0,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->call('openEventEditModal')
        ->set('editQuoteAmount', '500.00')
        ->set('confirmPassword', 'wrong-password')
        ->call('updateEventDetails')
        ->assertHasErrors(['confirmPassword']);
});

it('requires password when updating event details', function () {
    $booking = Booking::factory()->event()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->call('openEventEditModal')
        ->set('editQuoteAmount', '500.00')
        ->set('confirmPassword', '')
        ->call('updateEventDetails')
        ->assertHasErrors(['confirmPassword']);
});

it('pre-fills event details when opening edit modal', function () {
    $booking = Booking::factory()->event()->create([
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
        'total_amount' => 800,
        'event_type' => 'corporate',
        'pax' => 200,
        'is_buffet' => true,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->call('openEventEditModal')
        ->assertSet('editQuoteAmount', '800.00')
        ->assertSet('editEventType', 'corporate')
        ->assertSet('editPax', 200)
        ->assertSet('editIsBuffet', true);
});
