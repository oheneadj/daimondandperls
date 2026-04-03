<?php

use App\Enums\UserRole;
use App\Livewire\Customers\CustomerIndex;
use App\Models\Customer;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->adminUser = User::factory()->create(['role' => UserRole::SuperAdmin, 'is_active' => true]);
});

it('renders the customer index component', function () {
    actingAs($this->adminUser);
    Livewire::test(CustomerIndex::class)->assertStatus(200);
});

it('filters customers by registered role', function () {
    $registeredCustomer = Customer::factory()->create(['user_id' => User::factory()->create()->id]);
    $guestCustomer = Customer::factory()->create(['user_id' => null]);

    actingAs($this->adminUser);

    Livewire::test(CustomerIndex::class)
        ->set('role', 'registered')
        ->assertViewHas('customers', function ($customers) use ($registeredCustomer, $guestCustomer) {
            return $customers->contains($registeredCustomer) && ! $customers->contains($guestCustomer);
        });
});

it('filters customers by guest role', function () {
    $registeredCustomer = Customer::factory()->create(['user_id' => User::factory()->create()->id]);
    $guestCustomer = Customer::factory()->create(['user_id' => null]);

    actingAs($this->adminUser);

    Livewire::test(CustomerIndex::class)
        ->set('role', 'guest')
        ->assertViewHas('customers', function ($customers) use ($registeredCustomer, $guestCustomer) {
            return $customers->contains($guestCustomer) && ! $customers->contains($registeredCustomer);
        });
});

it('filters customers by active status', function () {
    $activeUser = User::factory()->create(['is_active' => true]);
    $inactiveUser = User::factory()->create(['is_active' => false]);

    $activeCustomer = Customer::factory()->create(['user_id' => $activeUser->id]);
    $inactiveCustomer = Customer::factory()->create(['user_id' => $inactiveUser->id]);

    actingAs($this->adminUser);

    Livewire::test(CustomerIndex::class)
        ->set('status', 'active')
        ->assertViewHas('customers', function ($customers) use ($activeCustomer, $inactiveCustomer) {
            return $customers->contains($activeCustomer) && ! $customers->contains($inactiveCustomer);
        });
});

it('filters customers by inactive status', function () {
    $activeUser = User::factory()->create(['is_active' => true]);
    $inactiveUser = User::factory()->create(['is_active' => false]);

    $activeCustomer = Customer::factory()->create(['user_id' => $activeUser->id]);
    $inactiveCustomer = Customer::factory()->create(['user_id' => $inactiveUser->id]);

    actingAs($this->adminUser);

    Livewire::test(CustomerIndex::class)
        ->set('status', 'inactive')
        ->assertViewHas('customers', function ($customers) use ($activeCustomer, $inactiveCustomer) {
            return $customers->contains($inactiveCustomer) && ! $customers->contains($activeCustomer);
        });
});
