<?php

use App\Livewire\Booking\BookingWizard;
use App\Livewire\Booking\CheckoutPayment;
use App\Livewire\Booking\EventInquiryWizard;
use App\Models\Booking;
use App\Models\Package;
use App\Services\CartService;
use Livewire\Livewire;

beforeEach(function () {
    // Ensure we have a package and it's in the cart
    $this->package = Package::factory()->create(['price' => 1000]);
    $cart = app(CartService::class);
    $cart->add($this->package->id);
});

test('booking wizard requires valid contact info', function () {
    Livewire::test(BookingWizard::class)
        ->set('currentStep', 2)
        ->set('name', '')
        ->set('phone', 'invalid')
        ->call('nextStep')
        ->assertHasErrors(['name', 'phone']);

    Livewire::test(BookingWizard::class)
        ->set('currentStep', 2)
        ->set('name', 'Ab') // Too short
        ->set('phone', '0244123456') // Valid Ghanaian format
        ->call('nextStep')
        ->assertHasErrors(['name'])
        ->assertHasNoErrors(['phone']);
});

test('event wizard requires valid event details', function () {
    Livewire::test(EventInquiryWizard::class)
        ->set('currentStep', 1)
        ->set('event_date', now()->subDay()->format('Y-m-d'))
        ->set('event_start_time', '12:00')
        ->set('event_end_time', '11:00')
        ->set('event_type', 'wedding')
        ->call('nextStep')
        ->assertHasErrors(['event_date', 'event_end_time']);
});

test('meal wizard skips event details entirely', function () {
    Livewire::test(BookingWizard::class)
        ->set('currentStep', 2)
        ->set('name', 'Test User')
        ->set('phone', '0244123456')
        ->call('nextStep')
        ->assertSet('currentStep', 3);
});

test('booking remains pending before payment', function () {
    $component = Livewire::test(BookingWizard::class)
        ->set('currentStep', 3)
        ->set('name', 'John Doe')
        ->set('phone', '0244111222')
        ->call('confirmBooking');

    $booking = Booking::latest()->first();
    expect($booking->status->value)->toBe('pending');
    expect($booking->payment_status->value)->toBe('unpaid');
});

test('booking becomes confirmed only after successful payment', function () {
    // Create a pending booking
    $booking = Booking::factory()->create([
        'status' => 'pending',
        'payment_status' => 'unpaid',
        'total_amount' => 1000,
    ]);

    // Simulate payment
    Livewire::test(CheckoutPayment::class, ['booking' => $booking])
        ->call('processCard');

    $booking->refresh();
    expect($booking->status->value)->toBe('confirmed');
    expect($booking->payment_status->value)->toBe('paid');
});
