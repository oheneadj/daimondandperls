<?php

declare(strict_types=1);

namespace App\Livewire\Packages;

use App\Models\Category;
use App\Models\Package;
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

    #[Url(history: true)]
    public ?int $categoryId = null;

    public function toggleSelection(int $packageId, \App\Services\CartService $cart): void
    {
        if ($this->isInCart($packageId, $cart)) {
            $cart->remove($packageId);
        } else {
            $cart->add($packageId);
        }
        $this->dispatch('cart-updated');
    }

    public function orderNow(int $packageId, \App\Services\CartService $cart): void
    {
        if (! $this->isInCart($packageId, $cart)) {
            $cart->add($packageId);
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

    public function render(\App\Services\CartService $cart): View
    {
        return view('livewire.packages.packages-browse', [
            'packages' => $this->getPackages(),
            'categories' => Category::whereHas('packages', function ($query) {
                $query->where('is_active', true);
            })->orderBy('name')->get(),
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
            ->when($this->categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->orderBy('sort_order', 'asc')
            ->get();
    }
}
