<?php

use App\Enums\BookingType;
use App\Enums\UserType;
use App\Livewire\Booking\BookingWizard;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Package;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
 * Progress a meal booking through all steps to the summary (Step 3).
 * Step flow: 1 (Review) → 2 (Contact) → 3 (Summary)
 */
function progressMealToSummary(\Livewire\Features\SupportTesting\Testable $component, array $contact = []): \Livewire\Features\SupportTesting\Testable
{
    $contact = array_merge([
        'name' => 'John Doe Test',
        'phone' => '0241234567',
        'email' => 'john@test.com',
    ], $contact);

    return $component
        ->call('nextStep') // 1 → 2
        ->assertSet('currentStep', 2)
        ->set('name', $contact['name'])
        ->set('phone', $contact['phone'])
        ->set('email', $contact['email'])
        ->call('nextStep') // 2 → 3
        ->assertSet('currentStep', 3);
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

it('starts on step 1 with review', function () {
    setupCart();

    Livewire::test(BookingWizard::class)
        ->assertSet('currentStep', 1);
});

it('follows 3-step progression: Review → Contact → Summary', function () {
    setupCart();

    Livewire::test(BookingWizard::class)
        ->assertSet('currentStep', 1)
        ->call('nextStep') // 1 → 2
        ->assertSet('currentStep', 2)
        ->set('name', 'Step Test')
        ->set('phone', '0241234567')
        ->call('nextStep') // 2 → 3
        ->assertSet('currentStep', 3);
});

it('can go back from summary to contact', function () {
    setupCart();

    $component = Livewire::test(BookingWizard::class);
    progressMealToSummary($component)
        ->call('previousStep') // 3 → 2
        ->assertSet('currentStep', 2);
});

it('can complete a meal booking with multiple items', function () {
    $category = Category::factory()->create();
    $package1 = Package::factory()->create(['category_id' => $category->id, 'price' => 500]);
    $package2 = Package::factory()->create(['category_id' => $category->id, 'price' => 200]);

    app(CartService::class)->add($package1->id, 1);
    app(CartService::class)->add($package2->id, 2);

    $component = Livewire::test(BookingWizard::class);
    progressMealToSummary($component)
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

    expect(app(CartService::class)->count())->toBe(0);
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

    $component = Livewire::test(BookingWizard::class);
    progressMealToSummary($component, ['name' => 'Snapshot Test'])
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

    $component = Livewire::actingAs($user)->test(BookingWizard::class);
    $component
        ->assertSet('name', 'OTP User')
        ->assertSet('phone', '0241234567')
        ->call('nextStep') // 1 → 2
        ->assertSet('currentStep', 2)
        ->call('nextStep') // 2 → 3
        ->assertSet('currentStep', 3)
        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::first();
    expect($booking)->not->toBeNull()
        ->and($booking->customer->user_id)->toBe($user->id)
        ->and($booking->customer->name)->toBe('OTP User');
});

it('creates guest customer when not authenticated', function () {
    setupCart(1, 100);

    $component = Livewire::test(BookingWizard::class);
    progressMealToSummary($component, [
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

it('sends otp from wizard using contact form phone number', function () {
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

it('verifies otp in wizard and auto-fills contact fields', function () {
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

it('shows error for invalid otp in wizard', function () {
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

    $component = Livewire::test(BookingWizard::class);
    progressMealToSummary($component, ['name' => 'John Doe No Event'])
        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::latest('id')->first();
    expect($booking->booking_type)->toBe(BookingType::Meal)
        ->and($booking->event_date)->toBeNull()
        ->and($booking->event_type)->toBeNull();
});
