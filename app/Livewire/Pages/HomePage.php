<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Category;
use App\Models\Package;
use App\Services\BookingWindowService;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Accra's Premier Catering Service")]
#[Layout('components.guest-layout')]
class HomePage extends Component
{
    public ?int $selectedCategory = null;

    public function selectCategory(?int $categoryId): void
    {
        $this->selectedCategory = $categoryId;
    }

    public function toggleSelection(int $packageId, CartService $cart): void
    {
        if ($cart->getCart()->has($packageId)) {
            $cart->remove($packageId);
        } else {
            $cart->add($packageId);
        }

        $this->dispatch('cart-updated');
    }

    public function orderNow(int $packageId, CartService $cart): void
    {
        if (! $cart->getCart()->has($packageId)) {
            $cart->add($packageId);
        }
        $this->redirect(route('checkout'));
    }

    public function render(CartService $cart, BookingWindowService $windowService): View
    {
        $categories = Category::whereHas('packages', function ($query) {
            $query->where('is_active', true);
        })->orderBy('name')->get();

        $windowStatuses = $categories->keyBy('id')->map(fn (Category $category) => $windowService->getStatus($category));

        return view('livewire.pages.home-page', [
            'packages' => $this->getPackages(),
            'categories' => $categories,
            'windowStatuses' => $windowStatuses,
            'cartItems' => $cart->getCart(),
        ]);
    }

    protected function getPackages(): Collection
    {
        return Package::query()
            ->with(['category'])
            ->where('is_active', true)
            ->when($this->selectedCategory, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->ordered()
            ->take(6)
            ->get();
    }
}
