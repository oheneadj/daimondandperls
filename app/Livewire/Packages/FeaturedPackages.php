<?php

declare(strict_types=1);

namespace App\Livewire\Packages;

use App\Models\Package;
use App\Services\BookingWindowService;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class FeaturedPackages extends Component
{
    public function toggleSelection(int $packageId, CartService $cart): void
    {
        if ($cart->getCart()->has($packageId)) {
            $cart->remove($packageId);
        } else {
            $cart->add($packageId);
        }

        $this->dispatch('cart-updated');
    }

    public function render(CartService $cart, BookingWindowService $windowService): View
    {
        $packages = Package::query()
            ->with(['categories', 'bookingWindows'])
            ->where('is_active', true)
            ->ordered()
            ->take(3)
            ->get();

        $activeWindows = $packages->keyBy('id')->map(
            fn (Package $p) => $windowService->getActiveWindow($p)
        );

        return view('livewire.packages.featured-packages', [
            'packages' => $packages,
            'activeWindows' => $activeWindows,
            'cartItems' => $cart->getCart(),
        ]);
    }
}
