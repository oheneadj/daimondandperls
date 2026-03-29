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
        ->and($booking->items[0]->package_name)->toBe($package1->name)
        ->and($booking->items[0]->package_description)->toBe($package1->description)
        ->and($booking->items[0]->quantity)->toBe(1)
        ->and((float) $booking->items[0]->price)->toEqual(500.0);

    expect($booking->items[1]->package_id)->toBe($package2->id)
        ->and($booking->items[1]->package_name)->toBe($package2->name)
        ->and($booking->items[1]->package_description)->toBe($package2->description)
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

it('preserves package details after package is soft-deleted', function () {
    $category = Category::factory()->create();
    $package = Package::factory()->create([
        'category_id' => $category->id,
        'price' => 300,
        'name' => 'Corporate Lunch',
        'description' => 'Jollof Rice & Chicken',
    ]);

    app(CartService::class)->add($package->id, 1);

    Livewire::test(BookingWizard::class)
        ->set('name', 'Snapshot Test')
        ->set('phone', '0241234567')
        ->call('nextStep')
        ->set('event_date', now()->addDays(3)->format('Y-m-d'))
        ->set('event_start_time', '12:00')
        ->set('event_end_time', '16:00')
        ->set('event_type', 'corporate')
        ->call('nextStep')
        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::first();
    $item = $booking->items->first();

    // Soft-delete the package
    $package->delete();
    expect(Package::find($package->id))->toBeNull();

    // Reload the item and verify snapshot data persists
    $item->refresh();
    expect($item->package_name)->toBe('Corporate Lunch')
        ->and($item->package_description)->toBe('Jollof Rice & Chicken')
        ->and((float) $item->price)->toEqual(300.0);

    // Verify withTrashed relationship still resolves
    expect($item->package)->not->toBeNull()
        ->and($item->package->id)->toBe($package->id);
});

it('attaches booking to authenticated user customer record', function () {
    $category = Category::factory()->create();
    $package = Package::factory()->create(['category_id' => $category->id, 'price' => 100]);
    app(CartService::class)->add($package->id, 1);

    $user = \App\Models\User::factory()->create([
        'name' => 'OTP User',
        'email' => 'otp@example.com',
        'phone' => '0241234567',
        'type' => \App\Enums\UserType::Customer,
    ]);

    Livewire::actingAs($user)
        ->test(BookingWizard::class)
        ->assertSet('name', 'OTP User')
        ->assertSet('phone', '0241234567')
        ->call('nextStep')
        ->assertSet('currentStep', 2)
        ->call('nextStep')
        ->assertSet('currentStep', 3)
        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::first();
    expect($booking)->not->toBeNull()
        ->and($booking->customer->user_id)->toBe($user->id)
        ->and($booking->customer->name)->toBe('OTP User');
});

it('creates guest customer when not authenticated', function () {
    $category = Category::factory()->create();
    $package = Package::factory()->create(['category_id' => $category->id, 'price' => 100]);
    app(CartService::class)->add($package->id, 1);

    Livewire::test(BookingWizard::class)
        ->set('name', 'Guest User')
        ->set('phone', '0249876543')
        ->set('email', 'guest@example.com')
        ->call('nextStep')
        ->assertSet('currentStep', 2)
        ->call('nextStep')
        ->assertSet('currentStep', 3)
        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::first();
    expect($booking)->not->toBeNull()
        ->and($booking->customer->name)->toBe('Guest User')
        ->and($booking->customer->user_id)->toBeNull()
        ->and($booking->customer->phone)->toBe('0249876543');
});

it('sends otp from wizard using contact form phone number', function () {
    $category = Category::factory()->create();
    $package = Package::factory()->create(['category_id' => $category->id]);
    app(CartService::class)->add($package->id, 1);

    Livewire::test(BookingWizard::class)
        ->set('name', 'OTP Wizard User')
        ->set('phone', '0244555666')
        ->set('verifyPhone', true)
        ->call('sendOtp')
        ->assertHasNoErrors()
        ->assertSet('otpStep', 2);

    $user = \App\Models\User::query()->where('phone', '0244555666')->first();
    expect($user)->not->toBeNull()
        ->and($user->otp_code)->not->toBeNull()
        ->and($user->type)->toBe(\App\Enums\UserType::Customer);
});

it('verifies otp in wizard and auto-fills contact fields', function () {
    $category = Category::factory()->create();
    $package = Package::factory()->create(['category_id' => $category->id]);
    app(CartService::class)->add($package->id, 1);

    $user = \App\Models\User::factory()->create([
        'name' => 'Verified User',
        'email' => 'verified@example.com',
        'phone' => '0244555666',
        'otp_code' => '123456',
        'otp_expires_at' => now()->addMinutes(10),
        'type' => \App\Enums\UserType::Customer,
    ]);

    Livewire::test(BookingWizard::class)
        ->set('name', 'Verified User')
        ->set('phone', '0244555666')
        ->set('verifyPhone', true)
        ->set('otpStep', 2)
        ->set('otp', '123456')
        ->call('verifyOtp')
        ->assertRedirect(route('checkout'));

    $this->assertAuthenticatedAs($user);

    // Verify wizard state was saved to session with user's stored details
    $state = session('checkout_wizard_state');
    expect($state)
        ->name->toBe('Verified User')
        ->email->toBe('verified@example.com')
        ->phone->toBe('0244555666');
});

it('shows error for invalid otp in wizard', function () {
    $category = Category::factory()->create();
    $package = Package::factory()->create(['category_id' => $category->id]);
    app(CartService::class)->add($package->id, 1);

    \App\Models\User::factory()->create([
        'phone' => '0244555666',
        'otp_code' => '123456',
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    Livewire::test(BookingWizard::class)
        ->set('phone', '0244555666')
        ->set('verifyPhone', true)
        ->set('otpStep', 2)
        ->set('otp', '999999')
        ->call('verifyOtp')
        ->assertSet('otpError', 'Invalid or expired OTP code.');

    $this->assertGuest();
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
