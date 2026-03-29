<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

trait ResolvesCustomer
{
    protected function resolveCustomer(): ?Customer
    {
        $user = Auth::user();
        $customer = $user->customer;

        if (! $customer) {
            $customer = Customer::query()
                ->where(function ($query) use ($user): void {
                    $query->where('phone', $user->phone)
                        ->orWhere('email', $user->email);
                })->first();

            if ($customer && ! $customer->user_id) {
                $customer->update(['user_id' => $user->id]);
            }
        }

        return $customer;
    }
}
