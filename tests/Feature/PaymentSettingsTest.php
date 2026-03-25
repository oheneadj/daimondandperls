<?php

use App\Livewire\Settings\General;
use App\Livewire\Settings\Notifications;
use App\Livewire\Settings\Payment;
use App\Models\Setting;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($this->user);
});

test('can update general business settings', function () {
    Livewire::test(General::class)
        ->set('settings.business_name', 'New Business Name')
        ->set('settings.business_email', 'new@business.com')
        ->set('settings.business_phone', '1234567890')
        ->set('settings.business_address', '123 New St')
        ->set('settings.booking_ref_prefix', 'NEW')
        ->call('updateSettings')
        ->assertHasNoErrors();

    expect(Setting::where('key', 'business_name')->value('value'))->toBe('New Business Name');
    expect(Setting::where('key', 'booking_ref_prefix')->value('value'))->toBe('NEW');
});

test('can update payment settings', function () {
    Livewire::test(Payment::class)
        ->set('settings.payment_gateway', 'paystack')
        ->set('settings.paystack_public_key', 'pk_test_123')
        ->set('settings.paystack_secret_key', 'sk_test_456')
        ->call('updateSettings')
        ->assertHasNoErrors();

    expect(Setting::where('key', 'payment_gateway')->value('value'))->toBe('paystack');
    expect(Setting::where('key', 'paystack_public_key')->value('value'))->toBe('pk_test_123');
});

test('can update global notification toggles and preference', function () {
    Livewire::test(Notifications::class)
        ->set('notification_preference', 'sms')
        ->set('sms_enabled', true)
        ->set('email_enabled', false)
        ->call('updateNotifications')
        ->assertHasNoErrors();

    expect($this->user->fresh()->notification_preference)->toBe(\App\Enums\NotificationPreference::Sms);
    expect((bool) Setting::where('key', 'sms_enabled')->value('value'))->toBeTrue();
    expect((bool) Setting::where('key', 'email_enabled')->value('value'))->toBeFalse();
});
