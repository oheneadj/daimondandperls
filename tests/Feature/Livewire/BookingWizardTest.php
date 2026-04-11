<?php

use App\Enums\BookingType;
use App\Enums\UserType;
use App\Livewire\Booking\BookingWizard;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Package;
use App\Models\User;
use App\Services\CartService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

uses(RefreshDatabase::class);

// ── Helpers ──────────────────────────────────────────────────

function setupCart(int $count = 1, float $price = 100): array
{
    $category = Category::factory()->create();
    $packages = [];
    for ($i = 0; $i < $count; $i++) {
        $packages[] = Package::factory()->create(['category_id' => $category->id, 'price' => $price]);
        app(CartService::class)->add($packages[$i]->id, 1);
    }

    return $packages;
}

/**
 * Fill in contact and payment fields on the single-screen booking wizard.
 */
function fillContact(\Livewire\Features\SupportTesting\Testable $component, array $contact = [], string $network = '13', string $number = '0241234567'): \Livewire\Features\SupportTesting\Testable
{
    $contact = array_merge([
        'name' => 'John Doe Test',
        'phone' => '0241234567',
        'email' => 'john@test.com',
    ], $contact);

    return $component
        ->set('name', $contact['name'])
        ->set('phone', $contact['phone'])
        ->set('email', $contact['email'])
        ->set('momoNetwork', $network)
        ->set('momoNumber', $number);
}

// ── Core Flow Tests ──────────────────────────────────────────

it('redirects to home if cart is empty', function () {
    Livewire::test(BookingWizard::class)
        ->assertRedirect(route('home'));
});

it('renders successfully with items in cart', function () {
    setupCart();

    Livewire::test(BookingWizard::class)
        ->assertStatus(200);
});

it('can complete a meal booking with multiple items', function () {
    $category = Category::factory()->create();
    $package1 = Package::factory()->create(['category_id' => $category->id, 'price' => 500]);
    $package2 = Package::factory()->create(['category_id' => $category->id, 'price' => 200]);

    app(CartService::class)->add($package1->id, 1);
    app(CartService::class)->add($package2->id, 2);

    fillContact(Livewire::test(BookingWizard::class))
        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::first();
    expect($booking)->not->toBeNull()
        ->and($booking->booking_type)->toBe(BookingType::Meal)
        ->and($booking->customer->name)->toBe('John Doe Test')
        ->and((float) $booking->total_amount)->toEqual(900.0)
        ->and($booking->items)->toHaveCount(2);

    expect($booking->items[0]->package_id)->toBe($package1->id)
        ->and($booking->items[0]->package_name)->toBe($package1->name)
        ->and($booking->items[0]->package_description)->toBe($package1->description)
        ->and($booking->items[0]->quantity)->toBe(1)
        ->and((float) $booking->items[0]->price)->toEqual(500.0);

    expect($booking->items[1]->package_id)->toBe($package2->id)
        ->and($booking->items[1]->quantity)->toBe(2)
        ->and((float) $booking->items[1]->price)->toEqual(200.0);

    // Cart is NOT cleared here — it persists until payment succeeds
    expect(app(CartService::class)->count())->toBe(3);
});

// ── Contact & Auth Tests ─────────────────────────────────────

it('pre-fills user details when logged in', function () {
    setupCart();

    $user = User::factory()->create([
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

    fillContact(Livewire::test(BookingWizard::class), ['name' => 'Snapshot Test'])
        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::first();
    $item = $booking->items->first();

    $package->delete();
    expect(Package::find($package->id))->toBeNull();

    $item->refresh();
    expect($item->package_name)->toBe('Corporate Lunch')
        ->and($item->package_description)->toBe('Jollof Rice & Chicken')
        ->and((float) $item->price)->toEqual(300.0);

    expect($item->package)->not->toBeNull()
        ->and($item->package->id)->toBe($package->id);
});

it('attaches booking to authenticated user customer record', function () {
    setupCart(1, 100);

    $user = User::factory()->create([
        'name' => 'OTP User',
        'email' => 'otp@example.com',
        'phone' => '0241234567',
        'type' => UserType::Customer,
    ]);

    Livewire::actingAs($user)
        ->test(BookingWizard::class)
        ->assertSet('name', 'OTP User')
        ->assertSet('phone', '0241234567')
        ->set('momoNetwork', '13')
        ->set('momoNumber', '0241234567')
        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::first();
    expect($booking)->not->toBeNull()
        ->and($booking->customer->user_id)->toBe($user->id)
        ->and($booking->customer->name)->toBe('OTP User');
});

it('creates guest customer when not authenticated', function () {
    setupCart(1, 100);

    fillContact(Livewire::test(BookingWizard::class), [
        'name' => 'Guest User',
        'phone' => '0249876543',
        'email' => 'guest@example.com',
    ])
        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::first();
    expect($booking)->not->toBeNull()
        ->and($booking->customer->name)->toBe('Guest User')
        ->and($booking->customer->user_id)->toBeNull()
        ->and($booking->customer->phone)->toBe('0249876543');
});

// ── OTP Tests ────────────────────────────────────────────────

it('sends otp using contact form phone number', function () {
    Notification::fake();
    setupCart();

    Livewire::test(BookingWizard::class)
        ->set('name', 'OTP Wizard User')
        ->set('phone', '0244555666')
        ->set('verifyPhone', true)
        ->call('sendOtp')
        ->assertHasNoErrors()
        ->assertSet('otpStep', 2);

    $user = User::query()->where('phone', '0244555666')->first();
    expect($user)->not->toBeNull()
        ->and($user->otp_code)->not->toBeNull()
        ->and($user->type)->toBe(UserType::Customer);
});

it('verifies otp and auto-fills contact fields', function () {
    setupCart();

    $user = User::factory()->create([
        'name' => 'Verified User',
        'email' => 'verified@example.com',
        'phone' => '0244555666',
        'otp_code' => '123456',
        'otp_expires_at' => now()->addMinutes(10),
        'type' => UserType::Customer,
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

    $state = session('checkout_wizard_state');
    expect($state)
        ->name->toBe('Verified User')
        ->email->toBe('verified@example.com')
        ->phone->toBe('0244555666');
});

it('shows error for invalid otp', function () {
    setupCart();

    User::factory()->create([
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

it('creates meal booking without event details', function () {
    setupCart();

    fillContact(Livewire::test(BookingWizard::class), ['name' => 'John Doe No Event'])
        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::latest('id')->first();
    expect($booking->booking_type)->toBe(BookingType::Meal)
        ->and($booking->event_date)->toBeNull()
        ->and($booking->event_type)->toBeNull();
});

// ── Delivery Location Tests ──────────────────────────────────────────────────

it('requires delivery location when locations are configured', function () {
    setupCart();

    \App\Models\Setting::create([
        'key' => 'delivery_locations',
        'value' => json_encode(['East Legon', 'Accra Mall']),
        'type' => \App\Enums\SettingType::Json,
        'group' => 'booking',
        'label' => 'Delivery Locations',
    ]);

    fillContact(Livewire::test(BookingWizard::class))
        ->call('confirmBooking')
        ->assertHasErrors(['deliveryLocation']);
});

it('saves delivery location on booking when configured', function () {
    setupCart();

    \App\Models\Setting::create([
        'key' => 'delivery_locations',
        'value' => json_encode(['East Legon', 'Accra Mall']),
        'type' => \App\Enums\SettingType::Json,
        'group' => 'booking',
        'label' => 'Delivery Locations',
    ]);

    fillContact(Livewire::test(BookingWizard::class))
        ->set('deliveryLocation', 'East Legon')
        ->call('confirmBooking')
        ->assertRedirect();

    expect(Booking::latest('id')->first()->delivery_location)->toBe('East Legon');
});

it('skips delivery location validation when no locations are configured', function () {
    setupCart();

    fillContact(Livewire::test(BookingWizard::class))
        ->call('confirmBooking')
        ->assertHasNoErrors(['deliveryLocation'])
        ->assertRedirect();
});

it('rejects an unlisted delivery location', function () {
    setupCart();

    \App\Models\Setting::create([
        'key' => 'delivery_locations',
        'value' => json_encode(['East Legon']),
        'type' => \App\Enums\SettingType::Json,
        'group' => 'booking',
        'label' => 'Delivery Locations',
    ]);

    fillContact(Livewire::test(BookingWizard::class))
        ->set('deliveryLocation', 'Some Random Place')
        ->call('confirmBooking')
        ->assertHasErrors(['deliveryLocation']);
});

it('persists delivery location through OTP wizard state', function () {
    setupCart();

    \App\Models\Setting::create([
        'key' => 'delivery_locations',
        'value' => json_encode(['Tema']),
        'type' => \App\Enums\SettingType::Json,
        'group' => 'booking',
        'label' => 'Delivery Locations',
    ]);

    // verifyOtp calls saveWizardState internally before redirecting
    User::factory()->create([
        'name' => 'OTP State User',
        'phone' => '0244555666',
        'otp_code' => '123456',
        'otp_expires_at' => now()->addMinutes(10),
        'type' => UserType::Customer,
    ]);

    Livewire::test(BookingWizard::class)
        ->set('deliveryLocation', 'Tema')
        ->set('name', 'OTP State User')
        ->set('phone', '0244555666')
        ->set('momoNetwork', '13')
        ->set('momoNumber', '0241234567')
        ->set('otpStep', 2)
        ->set('otp', '123456')
        ->call('verifyOtp');

    $state = session('checkout_wizard_state');
    expect($state['deliveryLocation'] ?? null)->toBe('Tema');
});

// ── Booking Window ────────────────────────────────────────────

it('saves scheduled_date on booking item when cart has windowed package', function () {
    $scheduledDate = Carbon::now()->addDays(3)->startOfDay();

    $category = Category::factory()->withBookingWindow()->create();
    $package = Package::factory()->create(['category_id' => $category->id, 'price' => 100]);
    app(CartService::class)->add($package->id, 1, scheduledDate: $scheduledDate);

    fillContact(Livewire::test(BookingWizard::class))
        ->call('confirmBooking')
        ->assertRedirect();

    $item = \App\Models\BookingItem::latest('id')->first();
    expect($item->scheduled_date->toDateString())->toBe($scheduledDate->toDateString());
});

it('saves null scheduled_date for packages without a booking window', function () {
    setupCart();

    fillContact(Livewire::test(BookingWizard::class))
        ->call('confirmBooking')
        ->assertRedirect();

    $item = \App\Models\BookingItem::latest('id')->first();
    expect($item->scheduled_date)->toBeNull();
});
