<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Enums\UserType;
use App\Models\Customer;
use App\Models\User;
use App\Services\LoyaltyService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    private function resolveReferredBy(): ?int
    {
        $code = session('referral_code');

        if (! $code) {
            return null;
        }

        return Customer::where('referral_code', $code)->value('id');
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'phone' => ['required', 'string', 'regex:/^(?:\+233|0)\d{9}$/', Rule::unique(User::class)],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input): User {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'] ?? null,
                'phone' => $input['phone'] ?? null,
                'password' => $input['password'],
                'type' => UserType::Customer,
            ]);

            $referredById = $this->resolveReferredBy();

            $customer = $user->customer()->create([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $input['phone'] ?? null,
                'referred_by_id' => $referredById,
            ]);

            $loyaltyService = app(LoyaltyService::class);
            $customer->update(['referral_code' => $loyaltyService->generateReferralCode($customer)]);

            session()->forget('referral_code');

            return $user;
        });
    }
}
