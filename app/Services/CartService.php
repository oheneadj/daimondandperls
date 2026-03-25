<?php

namespace App\Services;

use App\Models\Package;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'booking_cart';

    /**
     * Get the cart items linked to their Eloquent Package models.
     */
    public function getCart(): Collection
    {
        $cart = Session::get(self::SESSION_KEY, []);

        if (empty($cart)) {
            return collect();
        }

        $packageIds = array_keys($cart);
        $packages = Package::whereIn('id', $packageIds)->get()->keyBy('id');

        $items = collect($cart)->map(function ($quantity, $id) use ($packages) {
            $package = $packages->get($id);
            if (! $package) {
                return null;
            }

            return [
                'package' => $package,
                'quantity' => $quantity,
                'subtotal' => $package->price * $quantity,
            ];
        })->filter();

        return collect($items);
    }

    public function add(int $packageId, int $quantity = 1): void
    {
        // verify package exists before adding to prevent ghost items
        if (! Package::where('id', $packageId)->exists()) {
            return;
        }

        $cart = Session::get(self::SESSION_KEY, []);

        if (isset($cart[$packageId])) {
            $cart[$packageId] += $quantity;
        } else {
            $cart[$packageId] = $quantity;
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    public function updateQuantity(int $packageId, int $quantity): void
    {
        $cart = Session::get(self::SESSION_KEY, []);

        if ($quantity <= 0) {
            $this->remove($packageId);

            return;
        }

        if (isset($cart[$packageId])) {
            $cart[$packageId] = $quantity;
            Session::put(self::SESSION_KEY, $cart);
        }
    }

    public function remove(int $packageId): void
    {
        $cart = Session::get(self::SESSION_KEY, []);

        unset($cart[$packageId]);

        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function getTotal(): float
    {
        return $this->getCart()->sum('subtotal');
    }

    public function count(): int
    {
        return array_sum(Session::get(self::SESSION_KEY, []));
    }
}
