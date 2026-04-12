<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Livewire\Admin\Users\UserForm;
use App\Livewire\Settings\Password;
use App\Models\Role;
use App\Models\User;
use App\Notifications\AdminInvitationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $superAdminRole = Role::updateOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin', 'description' => 'Super Administrator']);
    $this->superAdmin = User::factory()->create(['type' => UserType::Admin]);
    $this->superAdmin->assignRole($superAdminRole);
    $this->role = Role::updateOrCreate(['slug' => 'staff'], ['name' => 'Staff', 'description' => 'Staff role']);
});

it('super admin can invite a new user', function () {
    Notification::fake();

    $this->actingAs($this->superAdmin);

    Livewire::test(UserForm::class)
        ->set('name', 'Jane Doe')
        ->set('email', 'jane@example.com')
        ->set('phone', '0241234567')
        ->set('selectedRole', $this->role->id)
        ->call('save');

    $user = User::where('email', 'jane@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->invitation_token)->not->toBeNull()
        ->and($user->invitation_sent_at)->not->toBeNull()
        ->and($user->must_change_password)->toBeTrue()
        ->and($user->type)->toBe(UserType::Admin);
});

it('invitation notification is queued on invite', function () {
    Notification::fake();

    $this->actingAs($this->superAdmin);

    Livewire::test(UserForm::class)
        ->set('name', 'Jane Doe')
        ->set('email', 'jane@example.com')
        ->set('phone', '0241234567')
        ->set('selectedRole', $this->role->id)
        ->call('save');

    $user = User::where('email', 'jane@example.com')->first();

    Notification::assertSentTo($user, AdminInvitationNotification::class);
});

it('valid invitation token marks accepted and redirects to login', function () {
    $user = User::factory()->create([
        'invitation_token' => 'valid-token-123',
        'invitation_sent_at' => now(),
        'invitation_accepted_at' => null,
    ]);

    $this->get(route('invitation.accept', 'valid-token-123'))
        ->assertRedirect(route('login'));

    $user->refresh();

    expect($user->invitation_token)->toBeNull()
        ->and($user->invitation_accepted_at)->not->toBeNull()
        ->and($user->email_verified_at)->not->toBeNull();
});

it('invalid invitation token returns 404', function () {
    $this->get(route('invitation.accept', 'bad-token'))
        ->assertNotFound();
});

it('already accepted token returns 404', function () {
    User::factory()->create([
        'invitation_token' => null,
        'invitation_accepted_at' => now()->subDay(),
    ]);

    $this->get(route('invitation.accept', 'used-token'))
        ->assertNotFound();
});

it('must_change_password banner is visible when flag is true', function () {
    $user = User::factory()->create([
        'type' => UserType::Admin,
        'must_change_password' => true,
    ]);

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertSee('Action Required: Change Your Password')
        ->assertSee('Change Password');
});

it('banner is not shown when must_change_password is false', function () {
    $user = User::factory()->create([
        'type' => UserType::Admin,
        'must_change_password' => false,
    ]);

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertDontSee('Action Required: Change Your Password');
});

it('updating password clears must_change_password flag', function () {
    $user = User::factory()->create([
        'type' => UserType::Admin,
        'must_change_password' => true,
        'password' => 'OldPass1!',
    ]);

    $this->actingAs($user);

    Livewire::test(Password::class)
        ->set('current_password', 'OldPass1!')
        ->set('password', 'NewPass1!')
        ->set('password_confirmation', 'NewPass1!')
        ->call('updatePassword');

    expect($user->fresh()->must_change_password)->toBeFalse();
});

it('password must meet policy', function () {
    $user = User::factory()->create([
        'type' => UserType::Admin,
        'password' => 'OldPass1!',
    ]);

    $this->actingAs($user);

    Livewire::test(Password::class)
        ->set('current_password', 'OldPass1!')
        ->set('password', 'weak')
        ->set('password_confirmation', 'weak')
        ->call('updatePassword')
        ->assertHasErrors(['password']);
});

it('invited user has role assigned', function () {
    Notification::fake();

    $this->actingAs($this->superAdmin);

    Livewire::test(UserForm::class)
        ->set('name', 'Jane Doe')
        ->set('email', 'jane@example.com')
        ->set('phone', '0241234567')
        ->set('selectedRole', $this->role->id)
        ->call('save');

    $user = User::where('email', 'jane@example.com')->first();

    expect($user->roles)->toHaveCount(1)
        ->and($user->roles->first()->id)->toBe($this->role->id);
});
