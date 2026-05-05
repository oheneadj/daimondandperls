<?php

declare(strict_types=1);

use App\Actions\Fortify\CreateNewUser;
use App\Enums\BookingStatus;
use App\Enums\SettingType;
use App\Livewire\Admin\Bookings\Show;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\LoyaltyTransaction;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Services\LoyaltyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function loyaltyAdminUser(): User
{
    $role = Role::updateOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin', 'description' => 'Super Administrator']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function registeredCustomer(int $points = 0): Customer
{
    $user = User::factory()->create();

    return Customer::factory()->create(['user_id' => $user->id, 'loyalty_points' => $points]);
}

// ── creditForBooking ──────────────────────────────────────────────────────────

it('awards points when a booking is marked completed', function () {
    $customer = registeredCustomer();
    $booking = Booking::factory()->create([
        'customer_id' => $customer->id,
        'total_amount' => 200.00,
        'status' => BookingStatus::Confirmed,
    ]);

    app(LoyaltyService::class)->creditForBooking($booking);

    // 200 GH₵ × 1 pt/GH₵ = 200 pts
    expect($customer->fresh()->loyalty_points)->toBe(200)
        ->and($booking->fresh()->points_earned)->toBe(200);

    expect(LoyaltyTransaction::where('customer_id', $customer->id)->where('type', 'earned')->exists())->toBeTrue();
});

it('does not award points to guest customers without user_id', function () {
    $customer = Customer::factory()->create(['user_id' => null, 'loyalty_points' => 0]);
    $booking = Booking::factory()->create([
        'customer_id' => $customer->id,
        'total_amount' => 200.00,
    ]);

    app(LoyaltyService::class)->creditForBooking($booking);

    expect($customer->fresh()->loyalty_points)->toBe(0);
    expect(LoyaltyTransaction::count())->toBe(0);
});

it('does not award points when loyalty is disabled', function () {
    Setting::updateOrCreate(
        ['key' => 'loyalty_enabled'],
        ['value' => '0', 'type' => SettingType::Boolean, 'group' => 'loyalty', 'label' => 'Loyalty Enabled']
    );
    Cache::forget('app_settings');

    $customer = registeredCustomer();
    $booking = Booking::factory()->create(['customer_id' => $customer->id, 'total_amount' => 200.00]);

    app(LoyaltyService::class)->creditForBooking($booking);

    expect($customer->fresh()->loyalty_points)->toBe(0);
});

// ── Admin completeBooking triggers creditForBooking ───────────────────────────

it('awards points when admin marks booking as completed via livewire', function () {
    Notification::fake();
    $admin = loyaltyAdminUser();
    $this->actingAs($admin);

    $customer = registeredCustomer();
    $booking = Booking::factory()->create([
        'customer_id' => $customer->id,
        'status' => BookingStatus::ReadyForDelivery,
        'total_amount' => 300.00,
    ]);

    Livewire::test(Show::class, ['booking' => $booking])
        ->call('completeBooking')
        ->assertHasNoErrors();

    expect($customer->fresh()->loyalty_points)->toBe(300)
        ->and(LoyaltyTransaction::where('type', 'earned')->exists())->toBeTrue();
});

// ── Referral bonus ────────────────────────────────────────────────────────────

it('credits referral bonus to referrer on referee first completed booking', function () {
    $referrer = registeredCustomer();
    $referrer->update(['referral_code' => 'TEST1234']);

    $refereeUser = User::factory()->create();
    $referee = Customer::factory()->create(['user_id' => $refereeUser->id, 'referred_by_id' => $referrer->id]);

    $booking = Booking::factory()->create([
        'customer_id' => $referee->id,
        'total_amount' => 100.00,
        'status' => BookingStatus::Confirmed,
    ]);

    app(LoyaltyService::class)->creditForBooking($booking);

    // referrer should have received referral bonus (default 50 pts)
    expect($referrer->fresh()->loyalty_points)->toBe(50);
    expect(LoyaltyTransaction::where('customer_id', $referrer->id)->where('type', 'referral_bonus')->exists())->toBeTrue();
});

it('does not credit referral bonus twice', function () {
    $referrer = registeredCustomer();
    $referrer->update(['referral_code' => 'TEST5678']);

    $refereeUser = User::factory()->create();
    $referee = Customer::factory()->create(['user_id' => $refereeUser->id, 'referred_by_id' => $referrer->id]);

    $booking1 = Booking::factory()->create(['customer_id' => $referee->id, 'total_amount' => 100.00]);
    $booking2 = Booking::factory()->create(['customer_id' => $referee->id, 'total_amount' => 100.00]);

    app(LoyaltyService::class)->creditForBooking($booking1);
    app(LoyaltyService::class)->creditForBooking($booking2);

    expect(LoyaltyTransaction::where('customer_id', $referrer->id)->where('type', 'referral_bonus')->count())->toBe(1);
    expect($referrer->fresh()->loyalty_points)->toBe(50);
});

// ── confirmRedemption ─────────────────────────────────────────────────────────

it('deducts points and logs transaction when redemption is confirmed', function () {
    $customer = registeredCustomer(500);
    $booking = Booking::factory()->create([
        'customer_id' => $customer->id,
        'total_amount' => 95.00,
        'discount_amount' => 5.00,
    ]);

    app(LoyaltyService::class)->confirmRedemption($booking);

    // GH₵5 × 100 = 500 pts deducted
    expect($customer->fresh()->loyalty_points)->toBe(0);
    expect(LoyaltyTransaction::where('customer_id', $customer->id)->where('type', 'redeemed')->exists())->toBeTrue();
});

it('does not deduct points when discount_amount is zero', function () {
    $customer = registeredCustomer(500);
    $booking = Booking::factory()->create([
        'customer_id' => $customer->id,
        'total_amount' => 100.00,
        'discount_amount' => 0,
    ]);

    app(LoyaltyService::class)->confirmRedemption($booking);

    expect($customer->fresh()->loyalty_points)->toBe(500);
    expect(LoyaltyTransaction::count())->toBe(0);
});

// ── Referral capture at registration ─────────────────────────────────────────

it('stores referred_by_id when registering with a valid referral code', function () {
    $referrer = registeredCustomer();
    $referrer->update(['referral_code' => 'REFA1234']);

    // Simulate what the register view does: store ref code in session
    session(['referral_code' => 'REFA1234']);

    $action = app(CreateNewUser::class);
    $newUser = $action->create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '0201234567',
        'password' => 'Password1!',
        'password_confirmation' => 'Password1!',
    ]);

    expect($newUser->customer->referred_by_id)->toBe($referrer->id);
    expect(session('referral_code'))->toBeNull();
});

it('creates customer without referred_by_id when no referral code in session', function () {
    $action = app(CreateNewUser::class);
    $newUser = $action->create([
        'name' => 'Bob Smith',
        'email' => 'bob@example.com',
        'phone' => '0207654321',
        'password' => 'Password1!',
        'password_confirmation' => 'Password1!',
    ]);

    expect($newUser->customer->referred_by_id)->toBeNull();
    expect($newUser->customer->referral_code)->not->toBeNull();
});
