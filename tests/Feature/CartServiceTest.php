<?php

declare(strict_types=1);

use App\Models\Package;
use App\Services\CartService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->cart = app(CartService::class);
    $this->package = Package::factory()->create(['price' => 100.00]);
});

afterEach(function () {
    $this->cart->clear();
});

it('adds a package to the cart', function () {
    $this->cart->add($this->package->id);

    expect($this->cart->count())->toBe(1);
});

it('stores scheduled_date when adding a windowed package', function () {
    $date = Carbon::parse('next Wednesday');

    $this->cart->add($this->package->id, scheduledDate: $date);

    $item = $this->cart->getCart()->first();

    expect($item['scheduled_date'])->not->toBeNull()
        ->and($item['scheduled_date']->toDateString())->toBe($date->toDateString());
});

it('returns null scheduled_date when not set', function () {
    $this->cart->add($this->package->id);

    $item = $this->cart->getCart()->first();

    expect($item['scheduled_date'])->toBeNull();
});

it('accumulates quantity when same package is added twice', function () {
    $this->cart->add($this->package->id, 2);
    $this->cart->add($this->package->id, 3);

    expect($this->cart->count())->toBe(5);
});

it('preserves scheduled_date when incrementing quantity', function () {
    $date = Carbon::parse('next Wednesday');
    $this->cart->add($this->package->id, 1, scheduledDate: $date);
    $this->cart->add($this->package->id, 1); // no date on second call

    $item = $this->cart->getCart()->first();

    expect($item['quantity'])->toBe(2)
        ->and($item['scheduled_date']->toDateString())->toBe($date->toDateString());
});

it('updates quantity keeping scheduled_date', function () {
    $date = Carbon::parse('next Wednesday');
    $this->cart->add($this->package->id, 1, scheduledDate: $date);
    $this->cart->updateQuantity($this->package->id, 5);

    $item = $this->cart->getCart()->first();

    expect($item['quantity'])->toBe(5)
        ->and($item['scheduled_date']->toDateString())->toBe($date->toDateString());
});

it('removes a package from the cart', function () {
    $this->cart->add($this->package->id);
    $this->cart->remove($this->package->id);

    expect($this->cart->count())->toBe(0);
});

it('clears the cart', function () {
    $this->cart->add($this->package->id);
    $this->cart->clear();

    expect($this->cart->count())->toBe(0);
});

it('computes the correct total', function () {
    $p2 = Package::factory()->create(['price' => 200.00]);
    $this->cart->add($this->package->id, 2); // 200
    $this->cart->add($p2->id, 1);            // 200

    expect($this->cart->getTotal())->toBe(400.0);
});
