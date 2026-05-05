<?php

declare(strict_types=1);

namespace App\Livewire\Customer;

use App\Services\LoyaltyService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.customer')]
class Loyalty extends Component
{
    use WithPagination;

    public function mount(): void
    {
        $customer = Auth::user()->customer;

        if ($customer) {
            app(LoyaltyService::class)->ensureReferralCode($customer);
        }
    }

    #[Title('Loyalty & Points')]
    public function render(): View
    {
        $customer = Auth::user()->customer;
        $loyalty = app(LoyaltyService::class);

        $transactions = $customer
            ? $customer->loyaltyTransactions()->latest()->paginate(15)
            : collect();

        $referralCount = $customer ? $customer->referrals()->count() : 0;

        $referralPointsEarned = $customer
            ? $customer->loyaltyTransactions()->where('type', 'referral_bonus')->sum('points')
            : 0;

        return view('livewire.customer.loyalty', [
            'customer' => $customer,
            'balance' => $customer?->loyalty_points ?? 0,
            'balanceGhc' => $loyalty->pointsToGhc($customer?->loyalty_points ?? 0),
            'redemptionRate' => $loyalty->redemptionRate(),
            'referralUrl' => $customer?->referral_code
                ? route('register', ['ref' => $customer->referral_code])
                : null,
            'referralCount' => $referralCount,
            'referralPointsEarned' => $referralPointsEarned,
            'transactions' => $transactions,
        ]);
    }
}
