<?php

use App\Livewire\Booking\TrackBooking;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders the track booking page', function () {
    $this->get(route('booking.track'))
        ->assertStatus(200);
});

it('validation fails with empty inputs', function () {
    Livewire::test(TrackBooking::class)
        ->call('track')
        ->assertHasErrors(['reference', 'phone']);
});

it('shows error message if booking not found', function () {
    Livewire::test(TrackBooking::class)
        ->set('reference', 'NON-EXISTENT')
        ->set('phone', '0241234567')
        ->call('track')
        ->assertSet('message', 'We couldn\'t find a booking matching those details. Please check and try again.');
});

it('redirects to payment if booking is unpaid', function () {
    $customer = Customer::factory()->create(['phone' => '0241112222']);
    $booking = Booking::factory()->create([
        'customer_id' => $customer->id,
        'reference' => 'CAT-TEST-001',
        'payment_status' => \App\Enums\PaymentStatus::Unpaid,
    ]);

    Livewire::test(TrackBooking::class)
        ->set('reference', 'CAT-TEST-001')
        ->set('phone', '0241112222')
        ->call('track')
        ->assertRedirect(route('booking.payment', 'CAT-TEST-001'));
});

it('redirects to confirmation if booking is already paid', function () {
    $customer = Customer::factory()->create(['phone' => '0243334444']);
    $booking = Booking::factory()->create([
        'customer_id' => $customer->id,
        'reference' => 'CAT-TEST-002',
        'payment_status' => \App\Enums\PaymentStatus::Paid,
    ]);

    Livewire::test(TrackBooking::class)
        ->set('reference', 'CAT-TEST-002')
        ->set('phone', '0243334444')
        ->call('track')
        ->assertRedirect(route('booking.confirmation', 'CAT-TEST-002'));
});
