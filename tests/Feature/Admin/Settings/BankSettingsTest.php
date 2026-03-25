<?php

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

use App\Livewire\Admin\Settings\AdminSettings;
use App\Livewire\Booking\CheckoutPayment;
use App\Models\Booking;
use App\Models\User;
use Livewire\Livewire;

test('admin can save bank details', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    Livewire::test(AdminSettings::class)
        ->set('bank_name', 'Test Bank')
        ->set('account_name', 'Test Account')
        ->set('account_number', '123456789')
        ->set('branch_code', 'BR001')
        ->call('saveBankDetails')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('settings', [
        'key' => 'bank_name',
        'value' => 'Test Bank',
    ]);
});

test('checkout payment displays saved bank details', function () {
    \App\Models\Setting::updateOrCreate(['key' => 'bank_name'], ['value' => 'Dynamic Bank', 'group' => 'bank', 'type' => \App\Enums\SettingType::String]);
    \App\Models\Setting::updateOrCreate(['key' => 'account_name'], ['value' => 'Dynamic Account', 'group' => 'bank', 'type' => \App\Enums\SettingType::String]);
    \App\Models\Setting::updateOrCreate(['key' => 'account_number'], ['value' => '987654321', 'group' => 'bank', 'type' => \App\Enums\SettingType::String]);

    $booking = Booking::factory()->create(['payment_status' => \App\Enums\PaymentStatus::Unpaid]);

    Livewire::test(CheckoutPayment::class, ['booking' => $booking])
        ->assertSet('bankName', 'Dynamic Bank')
        ->assertSet('accountName', 'Dynamic Account')
        ->assertSet('accountNumber', '987654321');
});
