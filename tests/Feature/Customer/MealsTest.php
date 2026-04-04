<?php

use App\Enums\BookingStatus;
use App\Livewire\Customer\Meals\Index;
use App\Livewire\Customer\Meals\Show;
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

test('customer can view meals index', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.meals.index'))
        ->assertOk();
});

test('guest is redirected from meals index', function () {
    $this->get(route('dashboard.meals.index'))
        ->assertRedirect(route('login'));
});

test('meals index only shows meal bookings', function () {
    $meal = Booking::factory()->meal()->create([
        'customer_id' => $this->customer->id,
        'status' => BookingStatus::Pending,
    ]);
    $event = Booking::factory()->event()->create([
        'customer_id' => $this->customer->id,
        'status' => BookingStatus::Pending,
    ]);

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->assertSee($meal->reference)
        ->assertDontSee($event->reference);
});

test('meals index can search by reference', function () {
    $meal1 = Booking::factory()->meal()->create([
        'customer_id' => $this->customer->id,
        'reference' => 'CAT-2026-00001',
    ]);
    $meal2 = Booking::factory()->meal()->create([
        'customer_id' => $this->customer->id,
        'reference' => 'CAT-2026-99999',
    ]);

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->set('search', '00001')
        ->assertSee('CAT-2026-00001')
        ->assertDontSee('CAT-2026-99999');
});

test('meals index can filter by status', function () {
    Booking::factory()->meal()->create([
        'customer_id' => $this->customer->id,
        'status' => BookingStatus::Pending,
    ]);
    Booking::factory()->meal()->create([
        'customer_id' => $this->customer->id,
        'status' => BookingStatus::Completed,
    ]);

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->set('status', BookingStatus::Pending->value)
        ->assertViewHas('bookings', fn ($bookings) => $bookings->count() === 1);
});

test('meals index does not show other customers bookings', function () {
    $otherCustomer = Customer::factory()->create();
    $otherMeal = Booking::factory()->meal()->create(['customer_id' => $otherCustomer->id]);

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->assertDontSee($otherMeal->reference);
});

// Show tests

test('customer can view own meal booking', function () {
    $meal = Booking::factory()->meal()->create([
        'customer_id' => $this->customer->id,
        'status' => BookingStatus::Confirmed,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.meals.show', $meal->reference))
        ->assertOk();
});

test('customer cannot view event booking on meal show page', function () {
    $event = Booking::factory()->event()->create([
        'customer_id' => $this->customer->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.meals.show', $event->reference))
        ->assertNotFound();
});

test('customer cannot view other customers meal booking', function () {
    $otherCustomer = Customer::factory()->create();
    $meal = Booking::factory()->meal()->create(['customer_id' => $otherCustomer->id]);

    $this->actingAs($this->user)
        ->get(route('dashboard.meals.show', $meal->reference))
        ->assertForbidden();
});

// Legacy redirect tests

test('legacy bookings index redirects to meals index', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.bookings.index'))
        ->assertRedirect(route('dashboard.meals.index'));
});

test('legacy booking show redirects meal to meals show', function () {
    $meal = Booking::factory()->meal()->create(['customer_id' => $this->customer->id]);

    $this->actingAs($this->user)
        ->get(route('dashboard.bookings.show', $meal->reference))
        ->assertRedirect(route('dashboard.meals.show', $meal->reference));
});

test('legacy booking show redirects event to events show', function () {
    $event = Booking::factory()->event()->create(['customer_id' => $this->customer->id]);

    $this->actingAs($this->user)
        ->get(route('dashboard.bookings.show', $event->reference))
        ->assertRedirect(route('dashboard.events.show', $event->reference));
});
