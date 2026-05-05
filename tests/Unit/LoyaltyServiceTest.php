<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\User;
use App\Services\LoyaltyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(LoyaltyService::class);
});

// ── Conversion helpers ────────────────────────────────────────────────────────

it('converts points to ghc correctly', function () {
    // default redemptionRate = 100 → 100 pts = GH₵1
    expect($this->service->pointsToGhc(100))->toBe(1.0)
        ->and($this->service->pointsToGhc(250))->toBe(2.5)
        ->and($this->service->pointsToGhc(0))->toBe(0.0);
});

it('converts ghc to points correctly using ceil', function () {
    // 1 GH₵ = 100 pts; partial amounts round up
    expect($this->service->ghcToPoints(1.0))->toBe(100)
        ->and($this->service->ghcToPoints(1.005))->toBe(101)
        ->and($this->service->ghcToPoints(0.0))->toBe(0);
});

it('calculates max discount based on percentage setting', function () {
    // default maxRedemptionPct = 20%
    expect($this->service->calculateMaxDiscount(100.0))->toBe(20.0)
        ->and($this->service->calculateMaxDiscount(50.0))->toBe(10.0);
});

// ── getRedeemablePoints ───────────────────────────────────────────────────────

it('returns correct redeemable data for a customer with points', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'loyalty_points' => 500]);

    // orderTotal = 100; max 20% = GH₵20; balance = 500pts = GH₵5 → limited by balance
    $result = $this->service->getRedeemablePoints($customer, 100.0);

    expect($result['balance'])->toBe(500)
        ->and($result['balance_ghc'])->toBe(5.0)
        ->and($result['max_discount_ghc'])->toBe(5.0)
        ->and($result['max_points'])->toBe(500);
});

it('caps max discount by order percentage when balance exceeds cap', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'loyalty_points' => 10000]);

    // orderTotal = 100; max 20% = GH₵20; balance = 10000pts = GH₵100 → capped at GH₵20
    $result = $this->service->getRedeemablePoints($customer, 100.0);

    expect($result['max_discount_ghc'])->toBe(20.0)
        ->and($result['max_points'])->toBe(2000);
});

// ── applyRedemption ───────────────────────────────────────────────────────────

it('returns the ghc discount for a valid redemption', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'loyalty_points' => 500]);

    $discount = $this->service->applyRedemption($customer, 500, 100.0);

    expect($discount)->toBe(5.0);
});

it('throws when customer has insufficient points', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'loyalty_points' => 50]);

    expect(fn () => $this->service->applyRedemption($customer, 500, 100.0))
        ->toThrow(InvalidArgumentException::class, 'Insufficient loyalty points.');
});

it('throws when redemption exceeds max discount cap', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'loyalty_points' => 10000]);

    // trying to redeem 3000pts = GH₵30 on a GH₵100 order (max GH₵20)
    expect(fn () => $this->service->applyRedemption($customer, 3000, 100.0))
        ->toThrow(InvalidArgumentException::class, 'exceeds the maximum allowed discount');
});

it('returns zero discount when zero points passed', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'loyalty_points' => 500]);

    expect($this->service->applyRedemption($customer, 0, 100.0))->toBe(0.0);
});

// ── generateReferralCode ──────────────────────────────────────────────────────

it('generates a referral code with name prefix and 4 digits', function () {
    $user = User::factory()->create(['name' => 'Alice Bob']);
    $customer = Customer::factory()->create(['user_id' => $user->id, 'name' => 'Alice Bob']);

    $code = $this->service->generateReferralCode($customer);

    expect($code)->toMatch('/^ALIC\d{4}$/')
        ->and(strlen($code))->toBe(8);
});

it('pads prefix with X when name is short', function () {
    $user = User::factory()->create(['name' => 'Al']);
    $customer = Customer::factory()->create(['user_id' => $user->id, 'name' => 'Al']);

    $code = $this->service->generateReferralCode($customer);

    expect($code)->toMatch('/^ALXX\d{4}$/');
});

// ── ensureReferralCode ────────────────────────────────────────────────────────

it('generates a referral code if missing', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'referral_code' => null]);

    $this->service->ensureReferralCode($customer);

    expect($customer->fresh()->referral_code)->not->toBeNull();
});

it('does not overwrite existing referral code', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'referral_code' => 'ABCD1234']);

    $this->service->ensureReferralCode($customer);

    expect($customer->fresh()->referral_code)->toBe('ABCD1234');
});

it('does not generate a code for guest customer without user_id', function () {
    $customer = Customer::factory()->create(['user_id' => null, 'referral_code' => null]);

    $this->service->ensureReferralCode($customer);

    expect($customer->fresh()->referral_code)->toBeNull();
});
