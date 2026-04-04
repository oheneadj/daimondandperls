<?php

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Livewire\Customer\Dashboard;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('customer can view dashboard', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    $this->actingAs($user)
        ->get(route('dashboard.index'))
        ->assertOk();
});

test('admin cannot access customer dashboard', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('dashboard.index'))
        ->assertStatus(403);
});

test('guest is redirected to login', function () {
    $this->get(route('dashboard.index'))
        ->assertRedirect(route('login'));
});

test('dashboard shows stat cards with correct data', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    Booking::factory()->create([
        'customer_id' => $customer->id,
        'status' => BookingStatus::Confirmed,
        'payment_status' => PaymentStatus::Paid,
        'total_amount' => 500.00,
    ]);

    Booking::factory()->create([
        'customer_id' => $customer->id,
        'status' => BookingStatus::Pending,
        'payment_status' => PaymentStatus::Unpaid,
        'total_amount' => 300.00,
    ]);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->assertSee('2')  // total bookings
        ->assertSee('500.00') // total spent
        ->assertOk();
});

test('dashboard links unlinked customer on first visit', function () {
    $user = User::factory()->customer()->create(['phone' => '0241234567']);
    $customer = Customer::factory()->create(['phone' => '0241234567', 'user_id' => null]);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->assertOk();

    expect($customer->fresh()->user_id)->toBe($user->id);
});

test('dashboard shows recent meals limited to 3', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    Booking::factory()->count(5)->meal()->create(['customer_id' => $customer->id]);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->assertViewHas('recentMeals', fn ($meals) => $meals->count() === 3);
});

test('dashboard shows recent events limited to 3', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    Booking::factory()->count(5)->event()->create(['customer_id' => $customer->id]);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->assertViewHas('recentEvents', fn ($events) => $events->count() === 3);
});

test('dashboard separates meals from events', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    Booking::factory()->count(2)->meal()->create(['customer_id' => $customer->id]);
    Booking::factory()->count(3)->event()->create(['customer_id' => $customer->id]);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->assertViewHas('recentMeals', fn ($meals) => $meals->count() === 2)
        ->assertViewHas('recentEvents', fn ($events) => $events->count() === 3);
});
