<?php

use App\Enums\PaymentMethod;
use App\Livewire\Customer\PaymentMethods;
use App\Models\Customer;
use App\Models\CustomerPaymentMethod;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
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
        ->assertSet('showOtpModal', true)
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

test('customer can add and verify a mobile money payment method', function () {
    Notification::fake();

    Livewire::actingAs($this->user)
        ->test(PaymentMethods::class)
        ->call('openForm')
        ->set('type', 'mobile_money')
        ->set('label', 'My Verified MoMo')
        ->set('provider', '13')
        ->set('accountNumber', '0241234567')
        ->call('save')
        ->assertSet('showOtpModal', true);

    $method = CustomerPaymentMethod::where('label', 'My Verified MoMo')->first();
    expect($method->verified_at)->toBeNull();
    expect($method->verification_code)->not->toBeNull();

    // Extract the OTP from the notification (verification_code is now hashed)
    $otp = null;
    Notification::assertSentTo($this->customer, \App\Notifications\OtpNotification::class, function ($notification) use (&$otp) {
        $otp = $notification->otp;

        return true;
    });

    Livewire::actingAs($this->user)
        ->test(PaymentMethods::class)
        ->set('verifyingId', $method->id)
        ->set('otpCode', $otp)
        ->call('verifyOtp')
        ->assertSet('showOtpModal', false)
        ->assertDispatched('toast');

    expect($method->refresh()->verified_at)->not->toBeNull();
});

test('existing payment method can be verified later', function () {
    Notification::fake();

    $method = CustomerPaymentMethod::factory()->create([
        'customer_id' => $this->customer->id,
        'verified_at' => null,
        'verification_code' => \Illuminate\Support\Facades\Hash::make('123456'),
    ]);

    Livewire::actingAs($this->user)
        ->test(PaymentMethods::class)
        ->call('resendOtp', $method->id)
        ->assertSet('showOtpModal', true);

    // Extract OTP from notification
    $otp = null;
    Notification::assertSentTo($this->customer, \App\Notifications\OtpNotification::class, function ($notification) use (&$otp) {
        $otp = $notification->otp;

        return true;
    });

    Livewire::actingAs($this->user)
        ->test(PaymentMethods::class)
        ->set('verifyingId', $method->id)
        ->set('otpCode', $otp)
        ->call('verifyOtp')
        ->assertSet('showOtpModal', false);

    expect($method->refresh()->isVerified())->toBeTrue();
});

test('customer cannot add duplicate phone number', function () {
    CustomerPaymentMethod::factory()->create([
        'customer_id' => $this->customer->id,
        'account_number' => '0241234567',
    ]);

    Livewire::actingAs($this->user)
        ->test(PaymentMethods::class)
        ->call('openForm')
        ->set('type', 'mobile_money')
        ->set('label', 'Duplicate MoMo')
        ->set('provider', '13')
        ->set('accountNumber', '0241234567')
        ->call('save')
        ->assertHasErrors(['accountNumber']);
});

test('editing a method allows keeping the same number', function () {
    $method = CustomerPaymentMethod::factory()->create([
        'customer_id' => $this->customer->id,
        'account_number' => '0241234567',
        'provider' => '13',
        'label' => 'Original',
        'verified_at' => now(),
    ]);

    Livewire::actingAs($this->user)
        ->test(PaymentMethods::class)
        ->call('edit', $method->id)
        ->set('label', 'Updated Label')
        ->call('save')
        ->assertHasNoErrors(['accountNumber']);

    expect($method->refresh()->label)->toBe('Updated Label');
});
