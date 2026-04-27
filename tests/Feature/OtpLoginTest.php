<?php

declare(strict_types=1);

use App\Livewire\Auth\OtpLogin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('blocks admin accounts from requesting an OTP', function (): void {
    $admin = User::factory()->admin()->create(['phone' => '0244000001']);

    Livewire::test(OtpLogin::class)
        ->set('phone', '0244000001')
        ->call('sendOtp')
        ->assertSet('step', 1)
        ->assertSet('error', 'No account found with this phone number. Please register first.');

    $admin->refresh();
    expect($admin->otp_code)->toBeNull();
});

it('allows customer accounts to request an OTP', function (): void {
    $customer = User::factory()->customer()->create(['phone' => '0244000002']);

    Livewire::test(OtpLogin::class)
        ->set('phone', '0244000002')
        ->call('sendOtp')
        ->assertHasNoErrors()
        ->assertSet('step', 2);

    $customer->refresh();
    expect($customer->otp_code)->not->toBeNull();
});
