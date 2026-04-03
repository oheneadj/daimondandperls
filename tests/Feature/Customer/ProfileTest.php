<?php

use App\Enums\NotificationPreference;
use App\Livewire\Customer\Profile;
use App\Models\Customer;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('customer can view profile settings', function () {
    $user = User::factory()->customer()->create();
    Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    $this->actingAs($user)
        ->get(route('dashboard.profile'))
        ->assertOk();
});

test('profile form is pre-filled with customer data', function () {
    $user = User::factory()->customer()->create([
        'name' => 'Kwame Mensah',
        'email' => 'kwame@test.com',
        'phone' => '0241234567',
        'notification_preference' => NotificationPreference::Sms,
    ]);
    Customer::factory()->create([
        'user_id' => $user->id,
        'name' => 'Kwame Mensah',
        'email' => 'kwame@test.com',
        'phone' => '0241234567',
    ]);

    Livewire::actingAs($user)
        ->test(Profile::class)
        ->assertSet('name', 'Kwame Mensah')
        ->assertSet('email', 'kwame@test.com')
        ->assertSet('phone', '0241234567')
        ->assertSet('notificationPreference', 'sms');
});

test('customer can update profile', function () {
    $user = User::factory()->customer()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    Livewire::actingAs($user)
        ->test(Profile::class)
        ->set('name', 'New Name')
        ->set('email', 'new@email.com')
        ->set('phone', '0551234567')
        ->set('notificationPreference', 'both')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('toast');

    expect($user->fresh())
        ->name->toBe('New Name')
        ->email->toBe('new@email.com')
        ->phone->toBe('0551234567')
        ->notification_preference->toBe(NotificationPreference::Both);

    expect($customer->fresh())
        ->name->toBe('New Name')
        ->email->toBe('new@email.com')
        ->phone->toBe('0551234567');
});

test('profile validation rejects invalid phone', function () {
    $user = User::factory()->customer()->create();
    Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    Livewire::actingAs($user)
        ->test(Profile::class)
        ->set('phone', 'invalid')
        ->call('save')
        ->assertHasErrors(['phone']);
});

test('profile validation requires name and email', function () {
    $user = User::factory()->customer()->create();
    Customer::factory()->create(['user_id' => $user->id, 'phone' => $user->phone]);

    Livewire::actingAs($user)
        ->test(Profile::class)
        ->set('name', '')
        ->set('email', '')
        ->call('save')
        ->assertHasErrors(['name'])
        ->assertHasNoErrors(['email']);
});
