<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\LoyaltyTransaction;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LoyaltyService
{
    public function isEnabled(): bool
    {
        return (bool) dpc_setting('loyalty_enabled', true);
    }

    public function pointsPerGhc(): int
    {
        return (int) dpc_setting('loyalty_points_per_ghc', 1);
    }

    public function referralBonus(): int
    {
        return (int) dpc_setting('loyalty_referral_bonus', 50);
    }

    public function redemptionRate(): int
    {
        return (int) dpc_setting('loyalty_redemption_rate', 100);
    }

    public function maxRedemptionPct(): int
    {
        return (int) dpc_setting('loyalty_max_redemption_pct', 20);
    }

    public function pointsToGhc(int $points): float
    {
        return round($points / $this->redemptionRate(), 2);
    }

    public function ghcToPoints(float $ghc): int
    {
        return (int) ceil($ghc * $this->redemptionRate());
    }

    public function calculateMaxDiscount(float $orderTotal): float
    {
        return round($orderTotal * ($this->maxRedemptionPct() / 100), 2);
    }

    /**
     * Returns redeemable info for a customer against a given order total.
     *
     * @return array{balance: int, balance_ghc: float, max_discount_ghc: float, max_points: int}
     */
    public function getRedeemablePoints(Customer $customer, float $orderTotal): array
    {
        $balance = $customer->loyalty_points;
        $maxDiscount = $this->calculateMaxDiscount($orderTotal);
        $maxFromPoints = $this->pointsToGhc($balance);
        $maxGhc = min($maxDiscount, $maxFromPoints);
        $maxPoints = $this->ghcToPoints($maxGhc);
        $maxPoints = min($maxPoints, $balance);

        return [
            'balance' => $balance,
            'balance_ghc' => $this->pointsToGhc($balance),
            'max_discount_ghc' => round($maxGhc, 2),
            'max_points' => $maxPoints,
        ];
    }

    /**
     * Validates and returns the GH₵ discount for the given points. Does NOT deduct yet.
     */
    public function applyRedemption(Customer $customer, int $points, float $orderTotal): float
    {
        if ($points <= 0) {
            return 0.0;
        }

        if ($customer->loyalty_points < $points) {
            throw new \InvalidArgumentException('Insufficient loyalty points.');
        }

        $maxDiscount = $this->calculateMaxDiscount($orderTotal);
        $ghcValue = $this->pointsToGhc($points);

        if ($ghcValue > $maxDiscount + 0.001) {
            throw new \InvalidArgumentException('Points redemption exceeds the maximum allowed discount for this order.');
        }

        return round($ghcValue, 2);
    }

    /**
     * Deducts points after payment is confirmed. No-op if discount_amount is 0.
     */
    public function confirmRedemption(Booking $booking): void
    {
        if (! $this->isEnabled()) {
            return;
        }

        if ($booking->discount_amount <= 0) {
            return;
        }

        $customer = $booking->customer;

        if (! $customer?->user_id) {
            return;
        }

        $points = $this->ghcToPoints((float) $booking->discount_amount);
        $points = min($points, $customer->loyalty_points);

        if ($points <= 0) {
            return;
        }

        DB::transaction(function () use ($customer, $points, $booking) {
            $customer->decrement('loyalty_points', $points);

            LoyaltyTransaction::create([
                'customer_id' => $customer->id,
                'booking_id' => $booking->id,
                'type' => 'redeemed',
                'points' => -$points,
                'description' => "Points redeemed for discount on booking #{$booking->reference}",
            ]);
        });
    }

    /**
     * Awards points when a booking is completed. Also triggers referral bonus if applicable.
     */
    public function creditForBooking(Booking $booking): void
    {
        if (! $this->isEnabled()) {
            return;
        }

        $customer = $booking->customer;

        if (! $customer?->user_id) {
            return;
        }

        $taxableAmount = (float) $booking->total_amount;
        $points = (int) floor($taxableAmount * $this->pointsPerGhc());

        if ($points <= 0) {
            return;
        }

        DB::transaction(function () use ($customer, $points, $booking) {
            $customer->increment('loyalty_points', $points);
            $booking->update(['points_earned' => $points]);

            LoyaltyTransaction::create([
                'customer_id' => $customer->id,
                'booking_id' => $booking->id,
                'type' => 'earned',
                'points' => $points,
                'description' => "Points earned for completed booking #{$booking->reference}",
            ]);
        });

        $this->checkAndCreditReferralBonus($customer);
    }

    /**
     * Credits referral bonus to the referrer if this is the referee's first completed booking.
     */
    private function checkAndCreditReferralBonus(Customer $referee): void
    {
        if (! $referee->referred_by_id) {
            return;
        }

        // Referral bonus transactions have booking_id = null, so we identify
        // whether this is the referee's first booking by counting their prior earned transactions.
        $alreadyCredited = LoyaltyTransaction::where('type', 'earned')
            ->where('customer_id', $referee->id)
            ->count() > 1;

        if ($alreadyCredited) {
            return;
        }

        $referrer = Customer::find($referee->referred_by_id);

        if (! $referrer?->user_id) {
            return;
        }

        $this->creditReferralBonus($referrer, $referee);
    }

    public function creditReferralBonus(Customer $referrer, Customer $referee): void
    {
        $points = $this->referralBonus();

        if ($points <= 0) {
            return;
        }

        DB::transaction(function () use ($referrer, $referee, $points) {
            $referrer->increment('loyalty_points', $points);

            LoyaltyTransaction::create([
                'customer_id' => $referrer->id,
                'booking_id' => null,
                'type' => 'referral_bonus',
                'points' => $points,
                'description' => "Referral bonus for {$referee->name}'s first completed booking",
            ]);
        });
    }

    public function creditForReview(Review $review): void
    {
        if (! $this->isEnabled()) {
            return;
        }

        $points = (int) dpc_setting('review_points_reward', 25);

        if ($points <= 0 || ! $review->customer_id) {
            return;
        }

        $customer = $review->customer;

        if (! $customer?->user_id) {
            return;
        }

        DB::transaction(function () use ($customer, $points, $review) {
            $customer->increment('loyalty_points', $points);
            $review->update(['points_awarded' => $points]);

            LoyaltyTransaction::create([
                'customer_id' => $customer->id,
                'booking_id' => $review->booking_id,
                'type' => 'review_bonus',
                'points' => $points,
                'description' => "Points awarded for reviewing booking #{$review->booking->reference}",
            ]);
        });
    }

    public function generateReferralCode(Customer $customer): string
    {
        do {
            $prefix = Str::upper(Str::substr(preg_replace('/[^a-zA-Z]/', '', $customer->name), 0, 4));
            $prefix = str_pad($prefix, 4, 'X');
            $code = $prefix.random_int(1000, 9999);
        } while (Customer::where('referral_code', $code)->exists());

        return $code;
    }

    public function ensureReferralCode(Customer $customer): void
    {
        if ($customer->referral_code) {
            return;
        }

        if (! $customer->user_id) {
            return;
        }

        $customer->update(['referral_code' => $this->generateReferralCode($customer)]);

        Log::info('LoyaltyService: generated referral code', [
            'customer_id' => $customer->id,
            'code' => $customer->referral_code,
        ]);
    }
}
