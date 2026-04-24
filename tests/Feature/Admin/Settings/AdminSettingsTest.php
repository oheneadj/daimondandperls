<?php

use App\Livewire\Admin\Settings\AdminSettings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('authenticated users can visit the settings page', function () {
    $this->actingAs($this->user)
        ->get(route('admin.settings.index'))
        ->assertOk()
        ->assertSeeLivewire(AdminSettings::class);
});

test('it can save business information', function () {
    Livewire::actingAs($this->user)
        ->test(AdminSettings::class)
        ->set('business_name', 'New Business Name')
        ->set('business_address', '123 Test St')
        ->set('business_phone', '0123456789')
        ->set('business_email', 'test@example.com')
        ->call('saveBusinessInfo')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('settings', [
        'key' => 'business_name',
        'value' => 'New Business Name',
    ]);
});

test('it can switch the active payment gateway', function () {
    Livewire::actingAs($this->user)
        ->test(AdminSettings::class)
        ->set('active_payment_gateway', 'moolre')
        ->call('savePaymentGateway')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('settings', [
        'key' => 'active_payment_gateway',
        'value' => 'moolre',
    ]);
});

test('it can update notification preferences', function () {
    Livewire::actingAs($this->user)
        ->test(AdminSettings::class)
        ->set('email_notifications', true)
        ->set('sms_notifications', true);

    $this->assertDatabaseHas('settings', [
        'key' => 'email_enabled',
        'value' => '1',
    ]);

    $this->assertDatabaseHas('settings', [
        'key' => 'sms_enabled',
        'value' => '1',
    ]);
});

test('it can update admin password', function () {
    $oldPassword = $this->user->password;

    Livewire::actingAs($this->user)
        ->test(\App\Livewire\Settings\Password::class)
        ->set('current_password', 'password') // Default factory password is 'password'
        ->set('password', 'NewSecurePassword123!')
        ->set('password_confirmation', 'NewSecurePassword123!')
        ->call('updatePassword')
        ->assertHasNoErrors();

    expect(Hash::check('NewSecurePassword123!', $this->user->fresh()->password))->toBeTrue();
});
