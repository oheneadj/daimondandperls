<?php

use App\Livewire\Booking\BookingWizard;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('admin cannot access customer dashboard', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('dashboard.index'))
        ->assertStatus(403);
});

test('customer cannot access admin dashboard', function () {
    $customerUser = User::factory()->customer()->create();

    $this->actingAs($customerUser)
        ->get(route('admin.dashboard'))
        ->assertStatus(403);
});

test('guest checkout creates customer without user account', function () {
    $package = \App\Models\Package::factory()->create(['price' => 100]);
    app(CartService::class)->add($package->id, 10);

    Livewire::test(BookingWizard::class)
        ->set('name', 'Guest User')
        ->set('phone', '0551234567')
        ->set('email', 'guest@example.com')
        ->call('confirmBooking')
        ->assertRedirect();

    $this->assertDatabaseHas('customers', [
        'phone' => '0551234567',
        'name' => 'Guest User',
    ]);
});

test('admin is redirected to admin dashboard after login', function () {
    $admin = User::factory()->admin()->create([
        'password' => Hash::make('password'),
    ]);

    $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));
});

test('customer is redirected to customer dashboard after login', function () {
    $customer = User::factory()->customer()->create([
        'password' => Hash::make('password'),
    ]);

    $this->post('/login', [
        'email' => $customer->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard.index'));
});
