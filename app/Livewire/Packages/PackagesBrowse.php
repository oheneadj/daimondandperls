<?php

declare(strict_types=1);

namespace App\Livewire\Packages;

use App\Models\Category;
use App\Models\Package;
use App\Services\BookingWindowService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Our Packages — Diamonds & Pearls')]
#[Layout('components.guest-layout')]
class PackagesBrowse extends Component
{
    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true, as: 'category')]
    public string $categorySlug = '';

    public function toggleSelection(int $packageId, \App\Services\CartService $cart, BookingWindowService $windowService): void
    {
        if ($this->isInCart($packageId, $cart)) {
            $cart->remove($packageId);
        } else {
            $package = Package::with('category')->findOrFail($packageId);
            $scheduledDate = $windowService->getScheduledDeliveryForPackage($package);
            $cart->add($packageId, scheduledDate: $scheduledDate);

            // Always notify customer of their scheduled delivery date for windowed packages
            if ($scheduledDate !== null) {
                $status = $windowService->getStatus($package->category);
                $this->dispatch('window-booking-info',
                    date: $scheduledDate->format('D, M j, Y'),
                    isNextWeek: ! $status['open'],
                );
            }
        }
        $this->dispatch('cart-updated');
    }

    public function orderNow(int $packageId, \App\Services\CartService $cart, BookingWindowService $windowService): void
    {
        if (! $this->isInCart($packageId, $cart)) {
            $package = Package::with('category')->findOrFail($packageId);
            $scheduledDate = $windowService->getScheduledDeliveryForPackage($package);
            $cart->add($packageId, scheduledDate: $scheduledDate);
        }
        $this->redirect(route('checkout'));
    }

    public function isInCart(int $packageId, \App\Services\CartService $cart): bool
    {
        return $cart->getCart()->has($packageId);
    }

    public function getCartProperty(\App\Services\CartService $cart): \Illuminate\Support\Collection
    {
        return $cart->getCart();
    }

    public function render(\App\Services\CartService $cart, BookingWindowService $windowService): View
    {
        $categories = Category::whereHas('packages', function ($query) {
            $query->where('is_active', true);
        })->orderBy('name')->get();

        $activeCategory = $this->categorySlug
            ? $categories->firstWhere('slug', $this->categorySlug)
            : null;

        $windowStatuses = $categories->keyBy('id')->map(fn (Category $category) => $windowService->getStatus($category));

        return view('livewire.packages.packages-browse', [
            'packages' => $this->getPackages(),
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'windowStatuses' => $windowStatuses,
            'cartItems' => $cart->getCart(),
            'cartCount' => $cart->count(),
        ]);
    }

    protected function getPackages(): Collection
    {
        return Package::query()
            ->with(['category'])
            ->where('is_active', true)
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($this->categorySlug, function ($query) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $this->categorySlug));
            })
            ->orderBy('sort_order', 'asc')
            ->get();
    }
}
