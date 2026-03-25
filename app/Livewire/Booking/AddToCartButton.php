<?php

namespace App\Livewire\Booking;

use App\Models\Package;
use App\Services\CartService;
use Livewire\Component;

class AddToCartButton extends Component
{
    public Package $package;

    public bool $isAdded = false;

    public string $style = 'large';

    public ?string $loading = null;

    public function mount(Package $package, string $style = 'large')
    {
        $this->package = $package;
        $this->style = $style;
    }

    public function addToCart(CartService $cart)
    {
        $this->loading = 'addToCart';
        $cart->add($this->package->id, 1);
        $this->isAdded = true;

        $this->dispatch('cart-updated');
        $this->loading = null;
    }

    public function bookNow(CartService $cart)
    {
        $this->loading = 'bookNow';
        $cart->add($this->package->id, 1);

        return $this->redirect(route('checkout'), navigate: true);
    }

    public function render()
    {
        return view('livewire.booking.add-to-cart-button');
    }
}
