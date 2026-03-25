<?php

use App\Models\User;
use App\Models\Customer;
use App\Enums\UserType;
use App\Livewire\Booking\BookingWizard;
use Livewire\Livewire;
use Illuminate\Support\Facades\Hash;
use App\Services\CartService;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('admin cannot access customer dashboard', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertStatus(403);
});

test('customer cannot access admin dashboard', function () {
    $customerUser = User::factory()->customer()->create();

    $this->actingAs($customerUser)
        ->get(route('admin.dashboard'))
        ->assertStatus(403);
});

test('guest checkout can create an account and auto-login', function () {
    // Add item to cart first
    $package = \App\Models\Package::factory()->create(['price' => 100]);
    app(CartService::class)->add($package->id, 10);

    Livewire::test(BookingWizard::class)
        ->set('name', 'Test Customer')
        ->set('phone', '0241234567')
        ->set('email', 'customer@example.com')
        ->set('createAccount', true)
        ->set('password', 'password123')
        ->call('confirmBooking')
        ->assertRedirect();

    $this->assertDatabaseHas('users', [
        'email' => 'customer@example.com',
        'type' => UserType::Customer->value,
    ]);

    $user = User::where('email', 'customer@example.com')->first();
    $this->assertDatabaseHas('customers', [
        'user_id' => $user->id,
        'phone' => '0241234567',
    ]);

    $this->assertAuthenticatedAs($user);
});

test('guest checkout without account creation only creates customer', function () {
    $package = \App\Models\Package::factory()->create(['price' => 100]);
    app(CartService::class)->add($package->id, 10);

    Livewire::test(BookingWizard::class)
        ->set('name', 'Guest User')
        ->set('phone', '0551234567')
        ->set('email', 'guest@example.com')
        ->set('createAccount', false)
        ->call('confirmBooking')
        ->assertRedirect();

    $this->assertDatabaseMissing('users', [
        'email' => 'guest@example.com',
    ]);

    $this->assertDatabaseHas('customers', [
        'phone' => '0551234567',
        'user_id' => null,
    ]);

    $this->assertGuest();
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
    ])->assertRedirect(route('dashboard'));
});
