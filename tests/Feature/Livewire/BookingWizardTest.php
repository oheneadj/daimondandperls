<?php

use App\Livewire\Booking\BookingWizard;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Package;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('redirects to home if cart is empty', function () {
    Livewire::test(BookingWizard::class)
        ->assertRedirect(route('home'));
});

it('renders successfully with items in cart', function () {
    $category = Category::factory()->create();
    $package = Package::factory()->create(['category_id' => $category->id, 'price' => 100]);
    app(CartService::class)->add($package->id, 1);

    Livewire::test(BookingWizard::class)
        ->assertStatus(200);
});

it('can progress through the steps and save a booking with multiple items', function () {
    $category = Category::factory()->create();
    $package1 = Package::factory()->create(['category_id' => $category->id, 'price' => 500]);
    $package2 = Package::factory()->create(['category_id' => $category->id, 'price' => 200]);

    app(CartService::class)->add($package1->id, 1);
    app(CartService::class)->add($package2->id, 2);

    Livewire::test(BookingWizard::class)
        ->set('name', 'John Doe Test')
        ->set('phone', '0241234567')
        ->set('email', 'john@test.com')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 2)

        ->set('event_date', now()->addDays(5)->format('Y-m-d'))
        ->set('event_start_time', '14:00')
        ->set('event_end_time', '18:00')
        ->set('event_type', 'wedding')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 3)

        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::first();
    expect($booking)->not->toBeNull()
        ->and($booking->customer->name)->toBe('John Doe Test')
        ->and((float) $booking->total_amount)->toEqual(900.0)
        ->and($booking->items)->toHaveCount(2);

    expect($booking->items[0]->package_id)->toBe($package1->id)
        ->and($booking->items[0]->quantity)->toBe(1)
        ->and((float) $booking->items[0]->price)->toEqual(500.0);

    expect($booking->items[1]->package_id)->toBe($package2->id)
        ->and($booking->items[1]->quantity)->toBe(2)
        ->and((float) $booking->items[1]->price)->toEqual(200.0);

    expect(app(CartService::class)->count())->toBe(0);
});

it('can progress with empty optional event details (subagent simulator)', function () {
    $category = Category::factory()->create();
    $package = Package::factory()->create(['category_id' => $category->id]);
    app(CartService::class)->add($package->id, 1);

    Livewire::test(BookingWizard::class)
        ->set('name', 'Jane Doe')
        ->set('phone', '0249876543')
        ->call('nextStep')
        ->assertSet('currentStep', 2)

        // Simulating the browser submitting empty strings
        // Providing required fields
        ->set('event_date', now()->addDays(2)->format('Y-m-d'))
        ->set('event_start_time', '10:00')
        ->set('event_end_time', '14:00')
        ->set('event_type', 'birthday')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 3)

        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::first();
    expect($booking->event_date->format('Y-m-d'))->toBe(now()->addDays(2)->format('Y-m-d'))
        ->and($booking->event_start_time)->toBe('10:00');
});

it('pre-fills user details when logged in', function () {
    $category = Category::factory()->create();
    $package = Package::factory()->create(['category_id' => $category->id]);
    app(CartService::class)->add($package->id, 1);

    $user = \App\Models\User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '0241112222',
    ]);

    Livewire::actingAs($user)
        ->test(BookingWizard::class)
        ->assertSet('name', 'John Doe')
        ->assertSet('email', 'john@example.com')
        ->assertSet('phone', '0241112222');
});

it('can book without event details', function () {
    $category = Category::factory()->create();
    $package = Package::factory()->create(['category_id' => $category->id]);
    app(CartService::class)->add($package->id, 1);

    Livewire::test(BookingWizard::class)
        ->set('name', 'John Doe No Event')
        ->set('phone', '0241234567')
        ->call('nextStep')
        ->assertSet('currentStep', 2)
        // Skip filling Step 3 fields
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 3)
        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::latest('id')->first();
    expect($booking->event_date)->toBeNull()
        ->and($booking->event_type)->toBeNull();
});
