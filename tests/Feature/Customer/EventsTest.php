<?php

use App\Enums\BookingStatus;
use App\Enums\EventType;
use App\Livewire\Customer\Events\Index;
use App\Livewire\Customer\Events\Show;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->customer()->create();
    $this->customer = Customer::factory()->create(['user_id' => $this->user->id, 'phone' => $this->user->phone]);
});

// Index tests

test('customer can view events index', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.events.index'))
        ->assertOk();
});

test('guest is redirected from events index', function () {
    $this->get(route('dashboard.events.index'))
        ->assertRedirect(route('login'));
});

test('events index only shows event bookings', function () {
    $event = Booking::factory()->event()->create([
        'customer_id' => $this->customer->id,
        'status' => BookingStatus::Pending,
    ]);
    $meal = Booking::factory()->meal()->create([
        'customer_id' => $this->customer->id,
        'status' => BookingStatus::Pending,
    ]);

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->assertSee($event->reference)
        ->assertDontSee($meal->reference);
});

test('events index can search by reference', function () {
    $event1 = Booking::factory()->event()->create([
        'customer_id' => $this->customer->id,
        'reference' => 'CAT-2026-10001',
    ]);
    $event2 = Booking::factory()->event()->create([
        'customer_id' => $this->customer->id,
        'reference' => 'CAT-2026-20002',
    ]);

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->set('search', '10001')
        ->assertSee('CAT-2026-10001')
        ->assertDontSee('CAT-2026-20002');
});

test('events index can filter by event type', function () {
    Booking::factory()->event()->create([
        'customer_id' => $this->customer->id,
        'event_type' => EventType::Wedding,
    ]);
    Booking::factory()->event()->create([
        'customer_id' => $this->customer->id,
        'event_type' => EventType::Corporate,
    ]);

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->set('eventType', EventType::Wedding->value)
        ->assertViewHas('bookings', fn ($bookings) => $bookings->count() === 1);
});

test('events index can filter by status', function () {
    Booking::factory()->event()->create([
        'customer_id' => $this->customer->id,
        'status' => BookingStatus::Pending,
    ]);
    Booking::factory()->event()->create([
        'customer_id' => $this->customer->id,
        'status' => BookingStatus::Completed,
    ]);

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->set('status', BookingStatus::Pending->value)
        ->assertViewHas('bookings', fn ($bookings) => $bookings->count() === 1);
});

test('events index does not show other customers events', function () {
    $otherCustomer = Customer::factory()->create();
    $otherEvent = Booking::factory()->event()->create(['customer_id' => $otherCustomer->id]);

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->assertDontSee($otherEvent->reference);
});

// Show tests

test('customer can view own event booking', function () {
    $event = Booking::factory()->event()->create([
        'customer_id' => $this->customer->id,
        'status' => BookingStatus::Confirmed,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.events.show', $event->reference))
        ->assertOk();
});

test('customer cannot view meal booking on event show page', function () {
    $meal = Booking::factory()->meal()->create([
        'customer_id' => $this->customer->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.events.show', $meal->reference))
        ->assertNotFound();
});

test('customer cannot view other customers event booking', function () {
    $otherCustomer = Customer::factory()->create();
    $event = Booking::factory()->event()->create(['customer_id' => $otherCustomer->id]);

    $this->actingAs($this->user)
        ->get(route('dashboard.events.show', $event->reference))
        ->assertForbidden();
});
