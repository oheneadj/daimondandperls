<?php

declare(strict_types=1);

namespace App\Livewire\Packages;

use App\Models\Category;
use App\Models\Package;
use App\Services\BookingWindowService;
use App\Services\CartService;
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

    public function toggleSelection(int $packageId, CartService $cart, BookingWindowService $windowService): void
    {
        if ($this->isInCart($packageId, $cart)) {
            $cart->remove($packageId);
        } else {
            $package = Package::with('bookingWindows')->findOrFail($packageId);
            $scheduledDate = $windowService->getScheduledDeliveryForPackage($package);
            $cart->add($packageId, scheduledDate: $scheduledDate);

            if ($scheduledDate !== null) {
                $activeWindow = $windowService->getActiveWindow($package);
                $status = $activeWindow ? $windowService->getStatus($activeWindow) : null;
                $this->dispatch('window-booking-info',
                    date: $scheduledDate->format('D, M j, Y'),
                    isNextWeek: $status ? ! $status['open'] : false,
                );
            }
        }
        $this->dispatch('cart-updated');
    }

    public function orderNow(int $packageId, CartService $cart, BookingWindowService $windowService): void
    {
        if (! $this->isInCart($packageId, $cart)) {
            $package = Package::with('bookingWindows')->findOrFail($packageId);
            $scheduledDate = $windowService->getScheduledDeliveryForPackage($package);
            $cart->add($packageId, scheduledDate: $scheduledDate);
        }
        $this->redirect(route('checkout'));
    }

    public function isInCart(int $packageId, CartService $cart): bool
    {
        return $cart->getCart()->has($packageId);
    }

    public function getCartProperty(CartService $cart): \Illuminate\Support\Collection
    {
        return $cart->getCart();
    }

    public function render(CartService $cart, BookingWindowService $windowService): View
    {
        $categories = Category::whereHas('packages', fn ($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();

        $activeCategory = $this->categorySlug
            ? $categories->firstWhere('slug', $this->categorySlug)
            : null;

        $packages = $this->getPackages();

        // Build active window map keyed by package ID
        $activeWindows = $packages->keyBy('id')->map(
            fn (Package $p) => $windowService->getActiveWindow($p)
        );

        return view('livewire.packages.packages-browse', [
            'packages' => $packages,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'activeWindows' => $activeWindows,
            'cartItems' => $cart->getCart(),
            'cartCount' => $cart->count(),
        ]);
    }

    protected function getPackages(): Collection
    {
        return Package::query()
            ->with(['categories', 'bookingWindows'])
            ->where('is_active', true)
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($this->categorySlug, function ($query) {
                $query->whereHas('categories', fn ($q) => $q->where('slug', $this->categorySlug));
            })
            ->orderBy('sort_order', 'asc')
            ->get();
    }
}
