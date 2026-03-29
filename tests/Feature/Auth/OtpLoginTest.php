<?php

use App\Enums\UserType;
use App\Livewire\Auth\OtpLogin;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('otp login component renders on login page', function () {
    $this->get(route('login'))->assertOk();
});

test('otp sends verification code for existing user', function () {
    $user = User::factory()->create(['phone' => '0244111222']);

    Livewire::test(OtpLogin::class)
        ->set('phone', '0244111222')
        ->call('sendOtp')
        ->assertHasNoErrors()
        ->assertSet('step', 2);

    $user->refresh();
    expect($user->otp_code)->not->toBeNull()
        ->and($user->otp_expires_at)->not->toBeNull();
});

test('otp auto-registers new user as customer type', function () {
    Livewire::test(OtpLogin::class)
        ->set('phone', '0244555666')
        ->call('sendOtp')
        ->assertHasNoErrors()
        ->assertSet('step', 2);

    $user = User::query()->where('phone', '0244555666')->first();
    expect($user)->not->toBeNull()
        ->and($user->type)->toBe(UserType::Customer)
        ->and($user->email)->toBeNull()
        ->and($user->customer)->not->toBeNull()
        ->and($user->customer->phone)->toBe('0244555666');
});

test('otp auto-register links existing customer record', function () {
    $customer = Customer::factory()->create([
        'phone' => '0244777888',
        'name' => 'Existing Customer',
        'email' => 'existing@example.com',
    ]);

    Livewire::test(OtpLogin::class)
        ->set('phone', '0244777888')
        ->call('sendOtp')
        ->assertHasNoErrors();

    $user = User::query()->where('phone', '0244777888')->first();
    expect($user->name)->toBe('Existing Customer')
        ->and($user->email)->toBe('existing@example.com')
        ->and($user->type)->toBe(UserType::Customer);

    $customer->refresh();
    expect($customer->user_id)->toBe($user->id);
});

test('otp verifies valid code and logs in user', function () {
    $user = User::factory()->create([
        'phone' => '0244111222',
        'otp_code' => '123456',
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    Livewire::test(OtpLogin::class)
        ->set('phone', '0244111222')
        ->set('step', 2)
        ->set('otp', '123456')
        ->call('verifyOtp')
        ->assertRedirect(route('dashboard.index'));

    $this->assertAuthenticatedAs($user);

    $user->refresh();
    expect($user->otp_code)->toBeNull()
        ->and($user->otp_expires_at)->toBeNull();
});

test('otp rejects invalid code', function () {
    User::factory()->create([
        'phone' => '0244111222',
        'otp_code' => '123456',
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    Livewire::test(OtpLogin::class)
        ->set('phone', '0244111222')
        ->set('step', 2)
        ->set('otp', '999999')
        ->call('verifyOtp')
        ->assertSet('error', 'Invalid or expired OTP code.');

    $this->assertGuest();
});

test('otp rejects expired code', function () {
    User::factory()->create([
        'phone' => '0244111222',
        'otp_code' => '123456',
        'otp_expires_at' => now()->subMinutes(1),
    ]);

    Livewire::test(OtpLogin::class)
        ->set('phone', '0244111222')
        ->set('step', 2)
        ->set('otp', '123456')
        ->call('verifyOtp')
        ->assertSet('error', 'Invalid or expired OTP code.');

    $this->assertGuest();
});

test('otp validates phone format', function () {
    Livewire::test(OtpLogin::class)
        ->set('phone', '12345')
        ->call('sendOtp')
        ->assertHasErrors(['phone']);
});
