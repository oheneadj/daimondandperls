<?php

namespace App\Livewire\Booking;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartSidebar extends Component
{
    public bool $isOpen = false;

    #[On('cart-updated')]
    public function handleCartUpdated()
    {
        // When cart is updated from AddToCart button, open the sidebar
        $this->isOpen = true;
    }

    #[On('toggle-cart')]
    public function toggleSidebar()
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function closeSidebar()
    {
        $this->isOpen = false;
    }

    public function incrementQuantity(int $packageId, CartService $cart)
    {
        $items = $cart->getCart();
        $item = $items->firstWhere('package.id', $packageId);

        if ($item) {
            $cart->updateQuantity($packageId, $item['quantity'] + 1);
            $this->dispatch('cart-updated')->self();
        }
    }

    public function decrementQuantity(int $packageId, CartService $cart)
    {
        $items = $cart->getCart();
        $item = $items->firstWhere('package.id', $packageId);

        if ($item) {
            $cart->updateQuantity($packageId, $item['quantity'] - 1);
            $this->dispatch('cart-updated')->self();
        }
    }

    public function removeItem(int $packageId, CartService $cart)
    {
        $cart->remove($packageId);
        $this->dispatch('cart-updated')->self();
    }

    public function render(CartService $cart)
    {
        return view('livewire.booking.cart-sidebar', [
            'cartItems' => $cart->getCart(),
            'cartTotal' => $cart->getTotal(),
            'cartCount' => $cart->count(),
        ]);
    }
}
