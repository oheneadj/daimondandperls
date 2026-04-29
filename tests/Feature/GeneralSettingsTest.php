<?php

use App\Livewire\Settings\General;
use App\Livewire\Settings\Notifications;
use App\Models\Setting;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('it can update general settings', function () {
    Livewire::test(General::class)
        ->set('settings.business_name', 'My New Site')
        ->set('settings.business_email', 'new@example.com')
        ->set('settings.business_phone', '0241111111')
        ->call('updateSettings')
        ->assertHasNoErrors()
        ->assertDispatched('toast');

    expect(Setting::where('key', 'business_name')->first()->value)->toBe('My New Site');
    expect(Setting::where('key', 'business_email')->first()->value)->toBe('new@example.com');
});

test('it can update notification preferences', function () {
    Livewire::test(Notifications::class)
        ->set('notification_preference', 'sms')
        ->call('updateNotifications')
        ->assertHasNoErrors()
        ->assertDispatched('toast');

    expect($this->user->refresh()->notification_preference)->toBe(\App\Enums\NotificationPreference::Sms);
});
