<?php

declare(strict_types=1);

namespace App\Livewire\Packages;

use App\Models\Category;
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
            ->with('category')
            ->where('is_active', true)
            ->ordered()
            ->take(3)
            ->get();

        $categoryIds = $packages->pluck('category_id')->filter()->unique();
        $categories = Category::whereIn('id', $categoryIds)->get();
        $windowStatuses = $categories->keyBy('id')->map(fn (Category $category) => $windowService->getStatus($category));

        return view('livewire.packages.featured-packages', [
            'packages' => $packages,
            'windowStatuses' => $windowStatuses,
            'cartItems' => $cart->getCart(),
        ]);
    }
}
