<?php

use App\Livewire\Customers\CustomerForm;
use App\Models\Customer;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('it can load the form for a customer with a null email', function () {
    $customer = Customer::factory()->create([
        'email' => null,
    ]);

    Livewire::test(CustomerForm::class, ['customer' => $customer])
        ->assertSet('email', '')
        ->assertStatus(200);
});

