<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'booking_cart';

    /**
     * Get the cart items linked to their Eloquent Package models.
     * Each item: ['package' => Package, 'quantity' => int, 'subtotal' => float, 'scheduled_date' => Carbon|null]
     */
    public function getCart(): Collection
    {
        $cart = Session::get(self::SESSION_KEY, []);

        if (empty($cart)) {
            return collect();
        }

        $packageIds = array_keys($cart);
        $packages = Package::whereIn('id', $packageIds)->get()->keyBy('id');

        return collect($cart)->map(function ($entry, $id) use ($packages) {
            $package = $packages->get($id);
            if (! $package) {
                return null;
            }

            // Support old scalar format (just a quantity int) and new array format
            $quantity = is_array($entry) ? ($entry['qty'] ?? 1) : (int) $entry;
            $scheduledDate = is_array($entry) && ! empty($entry['scheduled_date'])
                ? Carbon::parse($entry['scheduled_date'])
                : null;

            return [
                'package' => $package,
                'quantity' => $quantity,
                'subtotal' => $package->price * $quantity,
                'scheduled_date' => $scheduledDate,
            ];
        })->filter();
    }

    public function add(int $packageId, int $quantity = 1, ?Carbon $scheduledDate = null): void
    {
        // Verify package exists before adding to prevent ghost items
        if (! Package::where('id', $packageId)->exists()) {
            return;
        }

        $cart = Session::get(self::SESSION_KEY, []);

        if (isset($cart[$packageId])) {
            $existing = $cart[$packageId];
            $existingQty = is_array($existing) ? ($existing['qty'] ?? 1) : (int) $existing;
            $cart[$packageId] = [
                'qty' => $existingQty + $quantity,
                'scheduled_date' => $scheduledDate?->toDateString() ?? (is_array($existing) ? ($existing['scheduled_date'] ?? null) : null),
            ];
        } else {
            $cart[$packageId] = [
                'qty' => $quantity,
                'scheduled_date' => $scheduledDate?->toDateString(),
            ];
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
            $existing = $cart[$packageId];
            $scheduledDate = is_array($existing) ? ($existing['scheduled_date'] ?? null) : null;
            $cart[$packageId] = [
                'qty' => $quantity,
                'scheduled_date' => $scheduledDate,
            ];
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
        $cart = Session::get(self::SESSION_KEY, []);

        return (int) array_sum(array_map(
            fn ($entry) => is_array($entry) ? ($entry['qty'] ?? 1) : (int) $entry,
            $cart
        ));
    }
}
