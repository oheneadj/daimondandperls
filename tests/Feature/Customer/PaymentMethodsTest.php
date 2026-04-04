<?php

use App\Enums\PaymentMethod;
use App\Livewire\Customer\PaymentMethods;
use App\Models\Customer;
use App\Models\CustomerPaymentMethod;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->customer()->create();
    $this->customer = Customer::factory()->create(['user_id' => $this->user->id, 'phone' => $this->user->phone]);
});

test('customer can view payment methods page', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.payment-methods'))
        ->assertOk()
        ->assertSee('Payment Methods');
});

test('admin cannot access payment methods page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('dashboard.payment-methods'))
        ->assertStatus(403);
});

test('guest is redirected to login', function () {
    $this->get(route('dashboard.payment-methods'))
        ->assertRedirect(route('login'));
});

test('customer can add a mobile money payment method', function () {
    Livewire::actingAs($this->user)
        ->test(PaymentMethods::class)
        ->call('openForm')
        ->assertSet('showForm', true)
        ->set('type', 'mobile_money')
        ->set('label', 'My MTN MoMo')
        ->set('provider', '13')
        ->set('accountNumber', '0241234567')
        ->set('accountName', 'John Doe')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('showForm', false)
        ->assertDispatched('toast');

    expect($this->customer->paymentMethods)->toHaveCount(1);
    expect($this->customer->paymentMethods->first())
        ->type->toBe(PaymentMethod::MobileMoney)
        ->label->toBe('My MTN MoMo')
        ->is_default->toBeTrue();
});

test('first payment method is automatically set as default', function () {
    Livewire::actingAs($this->user)
        ->test(PaymentMethods::class)
        ->call('openForm')
        ->set('type', 'mobile_money')
        ->set('label', 'MoMo')
        ->set('provider', '13')
        ->set('accountNumber', '0241234567')
        ->call('save');

    expect($this->customer->paymentMethods()->first()->is_default)->toBeTrue();
});

test('customer can edit a payment method', function () {
    $method = CustomerPaymentMethod::factory()->create([
        'customer_id' => $this->customer->id,
        'label' => 'Old Label',
    ]);

    Livewire::actingAs($this->user)
        ->test(PaymentMethods::class)
        ->call('edit', $method->id)
        ->assertSet('showForm', true)
        ->assertSet('label', 'Old Label')
        ->set('label', 'New Label')
        ->call('save')
        ->assertSet('showForm', false);

    expect($method->fresh()->label)->toBe('New Label');
});

test('customer can delete a payment method', function () {
    $method = CustomerPaymentMethod::factory()->create([
        'customer_id' => $this->customer->id,
    ]);

    Livewire::actingAs($this->user)
        ->test(PaymentMethods::class)
        ->call('delete', $method->id);

    expect($this->customer->paymentMethods)->toHaveCount(0);
});

test('deleting default method promotes the oldest remaining', function () {
    $default = CustomerPaymentMethod::factory()->default()->create([
        'customer_id' => $this->customer->id,
    ]);
    $other = CustomerPaymentMethod::factory()->create([
        'customer_id' => $this->customer->id,
    ]);

    Livewire::actingAs($this->user)
        ->test(PaymentMethods::class)
        ->call('delete', $default->id);

    expect($other->fresh()->is_default)->toBeTrue();
});

test('customer can set a payment method as default', function () {
    $first = CustomerPaymentMethod::factory()->default()->create([
        'customer_id' => $this->customer->id,
    ]);
    $second = CustomerPaymentMethod::factory()->create([
        'customer_id' => $this->customer->id,
    ]);

    Livewire::actingAs($this->user)
        ->test(PaymentMethods::class)
        ->call('setDefault', $second->id);

    expect($first->fresh()->is_default)->toBeFalse();
    expect($second->fresh()->is_default)->toBeTrue();
});

test('validation requires label, provider, and account number', function () {
    Livewire::actingAs($this->user)
        ->test(PaymentMethods::class)
        ->call('openForm')
        ->set('label', '')
        ->set('provider', '')
        ->set('accountNumber', '')
        ->call('save')
        ->assertHasErrors(['label', 'provider', 'accountNumber']);
});

test('customer can cancel the form', function () {
    Livewire::actingAs($this->user)
        ->test(PaymentMethods::class)
        ->call('openForm')
        ->assertSet('showForm', true)
        ->call('cancel')
        ->assertSet('showForm', false)
        ->assertSet('editingId', null);
});
