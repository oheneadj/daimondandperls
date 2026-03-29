<?php

use App\Enums\UserType;
use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register with email and phone', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'phone' => '0244111222',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.dashboard', absolute: false));

    $this->assertAuthenticated();

    $user = User::query()->where('email', 'test@example.com')->first();
    expect($user->type)->toBe(UserType::Customer)
        ->and($user->phone)->toBe('0244111222')
        ->and($user->customer)->not->toBeNull()
        ->and($user->customer->phone)->toBe('0244111222');
});

test('new users can register with email only', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Email Only',
        'email' => 'emailonly@example.com',
        'phone' => '',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.dashboard', absolute: false));

    $this->assertAuthenticated();

    $user = User::query()->where('email', 'emailonly@example.com')->first();
    expect($user->type)->toBe(UserType::Customer)
        ->and($user->phone)->toBeNull();
});

test('new users can register with phone only', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Phone Only',
        'phone' => '0244333444',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertAuthenticated();

    $user = User::query()->where('phone', '0244333444')->first();
    expect($user->type)->toBe(UserType::Customer)
        ->and($user->email)->toBeNull()
        ->and($user->customer)->not->toBeNull();
});

test('registration fails without email and phone', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'No Contact',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors(['email', 'phone']);
    $this->assertGuest();
});
