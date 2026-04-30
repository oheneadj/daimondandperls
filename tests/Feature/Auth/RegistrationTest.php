<?php

use App\Enums\UserType;
use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register with both email and phone', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'phone' => '0244111222',
        'password' => 'P@ssword123!',
        'password_confirmation' => 'P@ssword123!',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('verification.phone', absolute: false));

    $this->assertAuthenticated();

    $user = User::query()->where('email', 'test@example.com')->first();
    expect($user->type)->toBe(UserType::Customer)
        ->and($user->phone)->toBe('0244111222')
        ->and($user->customer)->not->toBeNull()
        ->and($user->customer->phone)->toBe('0244111222');
});

test('registration fails if email is missing', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'No Email',
        'phone' => '0244111222',
        'password' => 'P@ssword123!',
        'password_confirmation' => 'P@ssword123!',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

test('registration fails if phone is missing', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'No Phone',
        'email' => 'test@example.com',
        'password' => 'P@ssword123!',
        'password_confirmation' => 'P@ssword123!',
    ]);

    $response->assertSessionHasErrors(['phone']);
    $this->assertGuest();
});

test('registration fails with invalid phone format', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Invalid Phone',
        'email' => 'test@example.com',
        'phone' => '12345',
        'password' => 'P@ssword123!',
        'password_confirmation' => 'P@ssword123!',
    ]);

    $response->assertSessionHasErrors(['phone']);
    $this->assertGuest();
});
