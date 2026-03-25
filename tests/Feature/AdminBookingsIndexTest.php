<?php

namespace Tests\Feature;

use App\Livewire\Admin\Bookings\Index;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('authenticated users can visit the admin bookings index', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('admin.bookings.index'));

    $response->assertOk();
    $response->assertSeeLivewire(Index::class);
    $response->assertSee('Bookings');
});

test('unauthenticated users are redirected from the admin bookings index', function () {
    $response = $this->get(route('admin.bookings.index'));
    $response->assertRedirect(route('login'));
});

test('admin bookings index displays a paginated list of bookings', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create 20 bookings
    $bookings = Booking::factory()->count(20)->create();

    // The latest booking should appear on the first page
    $latestBooking = $bookings->last();

    Livewire::test(Index::class)
        ->assertSee($latestBooking->reference)
        ->assertSee($latestBooking->customer->name);
});

test('admin bookings index can be searched by reference, customer name, and customer phone', function () {
    Booking::factory()->create(['reference' => 'CAT-UNIQUE-123']);

    $customerNameBooking = Booking::factory()->create();
    $customerNameBooking->customer->update(['name' => 'Unique Customer Name']);

    $customerPhoneBooking = Booking::factory()->create();
    $customerPhoneBooking->customer->update(['phone' => '0555999888']);

    Livewire::test(Index::class)
        // Search by reference
        ->set('search', 'UNIQUE-123')
        ->assertSee('CAT-UNIQUE-123')
        ->assertDontSee('Unique Customer Name')

        // Search by name
        ->set('search', 'Unique Customer Name')
        ->assertSee('Unique Customer Name')
        ->assertDontSee('CAT-UNIQUE-123')

        // Search by phone
        ->set('search', '0555999888')
        ->assertSee('0555999888')
        ->assertDontSee('CAT-UNIQUE-123');
});
