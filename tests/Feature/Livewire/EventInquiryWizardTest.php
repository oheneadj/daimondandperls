<?php

use App\Enums\BookingType;
use App\Enums\UserType;
use App\Livewire\Booking\EventInquiryWizard;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Package;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

// ── Helpers ──────────────────────────────────────────────────

function setupEventCart(int $count = 1, float $price = 100): array
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
 * Progress event wizard to summary (Step 4).
 * Step flow: 1 (Event Details) → 2 (Menu) → 3 (Contact) → 4 (Summary)
 */
function progressEventToSummary(\Livewire\Features\SupportTesting\Testable $component, array $contact = [], array $event = []): \Livewire\Features\SupportTesting\Testable
{
    $contact = array_merge([
        'name' => 'John Doe Test',
        'phone' => '0241234567',
        'email' => 'john@test.com',
    ], $contact);

    $event = array_merge([
        'event_date' => now()->addDays(5)->format('Y-m-d'),
        'event_start_time' => '14:00',
        'event_end_time' => '18:00',
        'event_type' => 'wedding',
    ], $event);

    return $component
        ->set('event_date', $event['event_date'])
        ->set('event_start_time', $event['event_start_time'])
        ->set('event_end_time', $event['event_end_time'])
        ->set('event_type', $event['event_type'])
        ->call('nextStep') // 1 → 2
        ->assertSet('currentStep', 2)
        ->call('nextStep') // 2 → 3
        ->assertSet('currentStep', 3)
        ->set('name', $contact['name'])
        ->set('phone', $contact['phone'])
        ->set('email', $contact['email'])
        ->call('nextStep') // 3 → 4
        ->assertSet('currentStep', 4);
}

// ── Core Flow Tests ──────────────────────────────────────────

it('allows empty cart for event bookings', function () {
    Livewire::test(EventInquiryWizard::class)
        ->assertStatus(200)
        ->assertSet('currentStep', 1);
});

it('renders with items in cart', function () {
    setupEventCart();

    Livewire::test(EventInquiryWizard::class)
        ->assertStatus(200);
});

it('follows 4-step progression', function () {
    Livewire::test(EventInquiryWizard::class)
        ->assertSet('currentStep', 1)
        ->set('event_date', now()->addDays(5)->format('Y-m-d'))
        ->set('event_start_time', '14:00')
        ->set('event_end_time', '18:00')
        ->call('nextStep') // 1 → 2
        ->assertSet('currentStep', 2)
        ->call('nextStep') // 2 → 3
        ->assertSet('currentStep', 3)
        ->set('name', 'Step Test')
        ->set('phone', '0241234567')
        ->call('nextStep') // 3 → 4
        ->assertSet('currentStep', 4);
});

it('can complete an event booking', function () {
    setupEventCart(1, 500);

    $component = Livewire::test(EventInquiryWizard::class);
    progressEventToSummary($component)
        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::first();
    expect($booking)->not->toBeNull()
        ->and($booking->booking_type)->toBe(BookingType::Event)
        ->and((float) $booking->total_amount)->toEqual(0.0)
        ->and($booking->event_type->value)->toBe('wedding')
        ->and($booking->pax)->toBeNull()
        ->and($booking->is_buffet)->toBeFalse();
});

it('can complete an event booking without cart items', function () {
    $component = Livewire::test(EventInquiryWizard::class);
    progressEventToSummary($component)
        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::first();
    expect($booking)->not->toBeNull()
        ->and($booking->booking_type)->toBe(BookingType::Event)
        ->and((float) $booking->total_amount)->toEqual(0.0)
        ->and($booking->items)->toHaveCount(0);
});

it('saves pax and buffet fields', function () {
    $component = Livewire::test(EventInquiryWizard::class);
    $component
        ->set('event_date', now()->addDays(10)->format('Y-m-d'))
        ->set('event_start_time', '10:00')
        ->set('event_end_time', '15:00')
        ->set('event_type', 'corporate')
        ->call('nextStep') // 1 → 2
        ->assertSet('currentStep', 2)
        ->set('pax', 150)
        ->set('is_buffet', true)
        ->call('nextStep') // 2 → 3
        ->assertSet('currentStep', 3)
        ->set('name', 'Pax Test')
        ->set('phone', '0241234567')
        ->call('nextStep') // 3 → 4
        ->assertSet('currentStep', 4)
        ->call('confirmBooking')
        ->assertRedirect();

    $booking = Booking::first();
    expect($booking->pax)->toBe(150)
        ->and($booking->is_buffet)->toBeTrue();
});

// ── Validation Tests ─────────────────────────────────────────

it('validates end time is after start time', function () {
    Livewire::test(EventInquiryWizard::class)
        ->set('event_date', now()->addDays(5)->format('Y-m-d'))
        ->set('event_start_time', '18:00')
        ->set('event_end_time', '14:00')
        ->call('nextStep')
        ->assertHasErrors(['event_end_time']);
});

it('requires event date', function () {
    Livewire::test(EventInquiryWizard::class)
        ->set('event_type', 'wedding')
        ->call('nextStep')
        ->assertHasErrors(['event_date']);
});

// ── Contact & Auth Tests ─────────────────────────────────────

it('pre-fills user details when logged in', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '0241112222',
    ]);

    Livewire::actingAs($user)
        ->test(EventInquiryWizard::class)
        ->assertSet('name', 'John Doe')
        ->assertSet('email', 'john@example.com')
        ->assertSet('phone', '0241112222');
});

// ── OTP Tests ────────────────────────────────────────────────

it('sends otp from event wizard', function () {
    Livewire::test(EventInquiryWizard::class)
        ->set('name', 'Event OTP User')
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

it('verifies otp in event wizard', function () {
    $user = User::factory()->create([
        'name' => 'Verified Event User',
        'email' => 'verified@example.com',
        'phone' => '0244555666',
        'otp_code' => '123456',
        'otp_expires_at' => now()->addMinutes(10),
        'type' => UserType::Customer,
    ]);

    Livewire::test(EventInquiryWizard::class)
        ->set('name', 'Verified Event User')
        ->set('phone', '0244555666')
        ->set('verifyPhone', true)
        ->set('otpStep', 2)
        ->set('otp', '123456')
        ->call('verifyOtp')
        ->assertRedirect(route('event-booking'));

    $this->assertAuthenticatedAs($user);

    $state = session('checkout_wizard_state');
    expect($state)
        ->name->toBe('Verified Event User')
        ->email->toBe('verified@example.com')
        ->phone->toBe('0244555666');
});
