<?php

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

use App\Livewire\Admin\Settings\AdminSettings;
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

test('admin bank details are persisted to settings table', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    Livewire::test(AdminSettings::class)
        ->set('bank_name', 'Dynamic Bank')
        ->set('account_name', 'Dynamic Account')
        ->set('account_number', '987654321')
        ->set('branch_code', '')
        ->call('saveBankDetails')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('settings', ['key' => 'bank_name', 'value' => 'Dynamic Bank'])
        ->assertDatabaseHas('settings', ['key' => 'account_name', 'value' => 'Dynamic Account'])
        ->assertDatabaseHas('settings', ['key' => 'account_number', 'value' => '987654321']);
});
