<?php

declare(strict_types=1);

namespace App\Livewire\Packages;

use App\Models\Package;
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

    public function render(CartService $cart): View
    {
        return view('livewire.packages.featured-packages', [
            'packages' => Package::query()
                ->with('category')
                ->where('is_active', true)
                ->ordered()
                ->take(3)
                ->get(),
            'cartItems' => $cart->getCart(),
        ]);
    }
}
